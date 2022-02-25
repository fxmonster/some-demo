<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class PaymentPolicy extends AbstractPolicy
{
    protected $permissionPrefix = 'payment';

    public function create(User $user): bool
    {
        return true;
    }

    public function creating(User $user, User $paymentUser): bool
    {
        // todo - check paymentUser->canPayOut (pay lock && played bets sum)

        return $user->is($paymentUser) || parent::create($user);
    }

    public function update(User $user, object $model): bool
    {
        return $user->is($model->user) || parent::update($user, $model);
    }

    public function read(User $user, Model $model): bool
    {
        return $user->is($model->user) || parent::read($user, $model);
    }
}
