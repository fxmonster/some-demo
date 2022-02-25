<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Collection;

class TagsHandleJob extends AbstractAsyncJob
{
    private Collection $users;

    public int $tries = 3;

    public function __construct(Collection $users)
    {
        $this->users = $users;
        $this->onQueue(self::getQueueName());
    }

    public function handle(): void
    {
        foreach ($this->users as $user) {
            /* @var User $user */
            foreach ($user->tags()->enabled()->wherePivot('completed_at', null)->get() as $tag) {
                $tag->object->handle();
            }
        }
    }
}
