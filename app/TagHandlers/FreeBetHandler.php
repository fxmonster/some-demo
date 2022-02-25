<?php

namespace App\TagHandlers;

use App\Enum\BonusAccountTypesEnum;
use App\Models\Bet;
use App\Models\Casino\Game;
use App\Models\Casino\GameTransaction;
use App\Models\User;
use Carbon\Carbon;
use Exchanger;

final class FreeBetHandler extends AbstractHandler
{
    protected function install(): void
    {
        $this->tag->config = [
            'amount' => 1000,
            'datetime_from' => Carbon::now(),
            'datetime_to' => Carbon::now()->addDays(14),
        ];
        $this->tag->order = 1;
        $this->tag->save();
    }

    public function handle(): void
    {
        if (!$this->handleAvailable()) {
            return;
        }

        if (!in_array($this->user->status, User::ACTIVE_STATUSES, true)) {
            return;
        }

        $availableByPeriod = Carbon::now()->between(
            $this->config['datetime_from'],
            $this->config['datetime_to']
        );

        if (!$availableByPeriod) {
            return;
        }

        $firstPayment = $this->getFirstPayment();

        if ($firstPayment) {
            $this->userTag->data['amount'] = $this->config['amount'];
            $this->userTag->data['amount_usd'] = round(
                Exchanger::convert(
                    config('currency.bonus_base_currency.code'),
                    config('currency.system_base_currency.code'),
                    $this->config['amount']
                ),
                2
            );
            $this->userTag->progress = 1;
        }
        $this->userTag->save();
    }

    public function check(GameTransaction|Bet|Game $bid): ?int
    {
        if (!$bid instanceof Bet || !$bid->freebet || $bid->betOrder->vip) {
            return null;
        }

        return BonusAccountTypesEnum::TYPE_FREE_BET;
    }

    public function charge(): void
    {
        if (!$this->chargeAvailable()) {
            return;
        }

        if (!$this->user->isActive()) {
            return;
        }

        $firstPayment = $this->getFirstPayment();

        if ($firstPayment) {
            $bonusAccount = $this->user->bonusAccount(BonusAccountTypesEnum::TYPE_FREE_BET);
            $this->moneyService->plusBonus($bonusAccount, $this->userTag->data['amount'], $this->userTag->id);

            $this->userTag->completed_at = Carbon::now();
            $this->userTag->save();
        }
    }
}
