<?php

namespace App\Services;

use App\Models\Bet;
use App\Models\BetItem;
use App\Models\Casino\GameTransaction;
use App\Models\Payment\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

final class AffiliateService
{
    public const AFFILIATE_QUEUE = 'affiliate';

    private const TYPE_REGISTER_USER = 1;
    private const TYPE_DEPOSIT = 7;
    private const TYPE_WITHDRAWAL = 8;
    private const TYPE_BET = 9;
    private const TYPE_BET_RESULT = 10;
    private const TYPE_DELETE_BET = 12;
    private const TYPE_CASINO_BET = 16;
    private const TYPE_CASINO_WIN = 17;

    private string $salt;
    private int $timestamp;

    private string $url;

    public function __construct()
    {
        $this->timestamp = Carbon::now()->timestamp;
        $this->salt = Config::get('services.affiliate.salt');
        $this->url = Config::get('services.affiliate.url');
    }

    protected function generateMeta(int $eventType, string $playerId, ?string $btag = null): array
    {
        return [
            'btag' => $btag,
            'eventType' => $eventType,
            'playerId' => $playerId,
            'signature' => md5("$this->timestamp|$this->salt|$playerId"),
            'timestamp' => $this->timestamp,
        ];
    }

    public function registerUser(User $user): bool|string
    {
        $data = [
            'data' => [
                'countryCode' => $user->country->code_alpha2,
            ],
            'meta' => $this->generateMeta(self::TYPE_REGISTER_USER, $user->uuid, $user->affiliate_id),
        ];

        return $this->sendPostRequest($data);
    }

    public function deposit(Payment $payment): bool|string
    {
        $data = [
            'data' => [
                'sum' => $payment->amount_usd,
                'currencyCode' => ExchangeService::BASE_CURRENCY_CODE,
                'id' => $payment->uuid,
                'paymentSystem' => $payment->system,
            ],
            'meta' => $this->generateMeta(self::TYPE_DEPOSIT, $payment->user->uuid),
        ];

        return $this->sendPostRequest($data);
    }

    public function withdrawal(Payment $payment): bool|string
    {
        $data = [
            'data' => [
                'sum' => $payment->amount_usd,
                'currencyCode' => ExchangeService::BASE_CURRENCY_CODE,
                'id' => $payment->uuid,
                'paymentSystem' => $payment->system,
            ],
            'meta' => $this->generateMeta(self::TYPE_WITHDRAWAL, $payment->user->uuid),
        ];

        return $this->sendPostRequest($data);
    }

    public function newBet(Bet $bet): bool|string
    {
        $data = $this->getBetData($bet, self::TYPE_BET);

        return $this->sendPostRequest($data);
    }

    public function betResult(Bet $bet): bool|string
    {
        $data = $this->getBetData($bet, self::TYPE_BET_RESULT);
        $data['data']['winSum'] = $bet->win_usd;

        return $this->sendPostRequest($data);
    }

    public function deleteBet(Bet $bet): bool|string
    {
        $data = $this->getBetData($bet, self::TYPE_DELETE_BET);
        $data['data']['winSum'] = $bet->win_usd;

        return $this->sendPostRequest($data);
    }

    public function casinoBet(GameTransaction $transaction): bool|string
    {
        $data = $this->getCasinoData($transaction, self::TYPE_CASINO_BET);
        $data['data']['betSum'] = $transaction->amount_usd;

        return $this->sendPostRequest($data);
    }

    public function casinoWin(GameTransaction $transaction): bool|string
    {
        $data = $this->getCasinoData($transaction, self::TYPE_CASINO_WIN);
        $data['data']['winSum'] = $transaction->amount_usd;
        return $this->sendPostRequest($data);
    }

    private function sendPostRequest(array $data): bool|string
    {
        $response = Http::post($this->url, $data);

        return true;
    }

    #[ArrayShape(['data' => "array", 'meta' => "array"])]
    private function getBetData(Bet $bet, int $type): array
    {
        return [
            'data' => [
                'coupon' => $this->getCouponData($bet),
                'isBonus' => (bool)$bet->bonus_account_id,
                'currencyCode' => ExchangeService::BASE_CURRENCY_CODE,
                'couponId' => $bet->id,
                'odd' => $bet->value,
                'betSum' => $bet->amount_usd,
                'winSum' => $bet->win_usd
            ],
            'meta' => $this->generateMeta($type, $bet->user->uuid),
        ];
    }

    private function getCouponData(Bet $bet): array
    {
        $coupon = [];
        foreach ($bet->betItems as $key => $item) {
            $coupon[$key]['gameId'] = $item->event_id;
            $coupon[$key]['type'] = BetItem::$type[(int)$item->type];
            $coupon[$key]['category'] = $item->sport->name;
            $coupon[$key]['league'] = $item->league->name;
            $coupon[$key]['team1'] = $item->event->team1;
            $coupon[$key]['team2'] = $item->event->team2;
        }

        return $coupon;
    }

    #[Pure]
    #[ArrayShape([
        'data' => "array",
        'meta' => "array"
    ])]
    private function getCasinoData(GameTransaction $transaction, int $type): array
    {
        return [
            'data' => [
                'currencyCode' => ExchangeService::BASE_CURRENCY_CODE,
                'id' => $transaction->uuid,
                'providerId' => $transaction->game->provider->id,
                'gameId' => $transaction->game->id,
            ],
            'meta' => $this->generateMeta($type, $transaction->user->uuid)
        ];
    }
}
