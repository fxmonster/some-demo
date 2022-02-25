<?php

namespace App\TagHandlers;

use App\Enum\BonusAccountTypesEnum;
use App\Models\Bet;
use App\Models\Casino\Game;
use App\Models\Casino\GameTransaction;
use Exchanger;
use Carbon\Carbon;

final class Bonus150PercentDepositCasinoHandler extends AbstractHandler
{
    protected function install(): void
    {
        $this->tag->config = [
            'wager' => 35, // Вейджер - google help me :L)
            'available_days' => 14, // Время в течении которого доступен отыгрыш
            'min_payment_amount_usd' => 10, // минимальная сумма первого депозита
            'bonus_multiplier' => 1.5, // Множитель начисления бонуса на первый депозит (1.5 = 150% бонус на депозит)
            'max_bonus_amount_usd' => 500, // максимальная сумма начисленного бонуса
        ];
        $this->tag->order = 3;
        $this->tag->save();
    }

    public function charge(): void
    {
        if (!$this->chargeAvailable()) {
            return;
        }

        $firstPayment = $this->getFirstPayment();
        if (!$firstPayment) {
            return;
        }

        $bonusAccount = $this->user->bonusAccount(BonusAccountTypesEnum::TYPE_BONUS_CASINO);

        $chargingAmount = Exchanger::convert(
            $firstPayment->currency->code,
            $bonusAccount->currency->code,
            $firstPayment->amount * $this->config['bonus_multiplier']
        );

        $maxChargingAmount = Exchanger::convertFromBase(
            $bonusAccount->currency->code,
            $this->config['max_bonus_amount_usd']
        );

        if ($chargingAmount > $maxChargingAmount) {
            $chargingAmount = $maxChargingAmount;
        }

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
        // The amount is not less than 10 USD
        if ($firstPayment->amount_usd < $this->config['min_payment_amount_usd']) {
            return;
        }

        // Available 14 days after replenishment
        $created = $firstPayment->created_at;
        $now = Carbon::now();
        if ($created->diff($now)->days > $this->config['available_days']) {
            return;
        }

        $wagedAmountUsd = $firstPayment->amount_usd * $this->config['wager'];

        $wagedAmount = Exchanger::convert(
            $firstPayment->currency->code,
            config('currency.bonus_base_currency.code'),
            $firstPayment->amount * $this->config['wager']
        );

        $this->userTag->data['amount_usd'] = round($wagedAmountUsd, 2);
        $this->userTag->data['amount'] = round($wagedAmount, 2);

        $betsCasinoAmountUsd = GameTransaction::where([
                                                          'user_id' => $this->user->id,
                                                          'type' => GameTransaction::TYPE_BET
                                                      ])->sum('amount_usd');

        $this->userTag->progress = match (true) {
            $betsCasinoAmountUsd === 0.0 => 0.0,
            $betsCasinoAmountUsd >= $wagedAmountUsd => 1.0,
            default => $betsCasinoAmountUsd / $wagedAmountUsd,
        };

        $this->userTag->save();
    }

    public function check(GameTransaction|Bet|Game $bid): ?int
    {
        if (!$bid instanceof GameTransaction && !$bid instanceof Game) {
            return null;
        }

        return BonusAccountTypesEnum::TYPE_BONUS_CASINO;
    }
}
