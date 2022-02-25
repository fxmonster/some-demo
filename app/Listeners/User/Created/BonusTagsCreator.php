<?php

namespace App\Listeners\User\Created;

use App\Enum\BonusAccountTypesEnum;
use App\Events\User\UserCreated;
use App\Models\Tag;

final class BonusTagsCreator
{
    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        // Добавление Пользователю активного Тега выбранного типа при регистрации (Бонус Беттинг/Казино)
        $tags[] = Tag::whereBonusAccountTypeId($event->bonusType)->enabled()->first();

        // Добавление Пользователю Тега Freebet при регистрации
        $tags[] = Tag::whereBonusAccountTypeId(BonusAccountTypesEnum::TYPE_FREE_BET)->enabled()->first();

        foreach ($tags as $tag) {
            if ($tag && !$user->tags()->find($tag->id)) {
                $user->tags()->attach($tag);
            }
        }
    }
}
