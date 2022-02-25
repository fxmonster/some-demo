<?php

namespace App\TagHandlers;

use App\Enum\BonusAccountTypesEnum;
use App\Exceptions\MoneyService\UserMoneyChangeException;
use Exchanger;
use App\Models\Bet;
use App\Models\Casino\Game;
use App\Models\Casino\GameTransaction;
use Carbon\Carbon;

class Bonus100PercentDepositHandler extends AbstractHandler
{
    protected function install(): void
    {
        $this->tag->config = [
            'wager' => 7,
        ];
        $this->tag->order = 2;
        $this->tag->save();
    }

    /**
     * Начисление бонусных денег, если все условия выполнены и вознаграждение еще не начислено
     *
     * @throws UserMoneyChangeException
     * @throws \Throwable
     */
    public function charge(): void
    {
        if (!$this->chargeAvailable()) {
            return;
        }

        $firstPayment = $this->getFirstPayment();
        if (!$firstPayment) {
            return;
        }

        $bonusAccount = $this->user->bonusAccount(BonusAccountTypesEnum::TYPE_BONUS_BET);

        $chargingAmount = Exchanger::convert(
            $firstPayment->currency->code,
            $bonusAccount->currency->code,
            $firstPayment->amount
        );

        $this->moneyService->plusBonus($bonusAccount, $chargingAmount, $this->userTag->id);

        $this->userTag->completed_at = Carbon::now();
        $this->userTag->save();
    }

    public function handle(): void
    {
        if (!$this->handleAvailable()) {
            return;
        }

        $firstPayment = $this->getFirstPayment();

        if (!$firstPayment) {
            return;
        }

        $wagedAmountUsd = $firstPayment->amount_usd * $this->config['wager'];
        $wagedAmount = Exchanger::convertFromBase(config('currency.bonus_base_currency.code'), $wagedAmountUsd);

        $this->userTag->data['amount_usd'] = round($wagedAmountUsd, 2);
        $this->userTag->data['amount'] = round($wagedAmount, 2);

        $betsAmountUsd = $this->user->bets()
            ->whereIn('status', Bet::CALCULATED_STATUSES)
            ->whereHas('betItems', function ($query){
                $query->where('value', '>', 1.6);
            })
            ->sum('amount_usd');

        $this->userTag->progress = match (true) {
            $betsAmountUsd === 0.0 => 0.0,
            $betsAmountUsd >= $wagedAmountUsd => 1.0,
            default => $betsAmountUsd / $wagedAmountUsd,
        };

        $this->userTag->save();
    }

    public function check(Bet|GameTransaction|Game $bid): ?int
    {
        if (!$bid instanceof Bet || $bid->betOrder->vip) {
            return null;
        }

        return BonusAccountTypesEnum::TYPE_BONUS_BET;
    }
}
