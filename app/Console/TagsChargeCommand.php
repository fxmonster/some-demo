<?php

namespace App\Console\Commands;

use App\Enum\BonusAccountTypesEnum;
use App\Models\User;
use Illuminate\Console\Command;

class TagsChargeCommand extends Command
{
    protected $signature = 'tags:charge';

    protected $description = 'Charge Tags Command';

    public function handle(): void
    {
        $this->info('Processing all user Tag Charges...');

        User::active()->chunkById(100, function ($users) {
            foreach ($users as $user) {
                foreach ($user->tags()->enabled()
                             ->byTypes(BonusAccountTypesEnum::AUTOMATIC_ENROLLMENT)
                             ->wherePivot('completed_at', null)
                             ->wherePivot('progress', '>=', 1.0)->get() as $tag) {
                    $tag->object->charge();
                }
            }
        });

        $this->info('Done!');
    }
}
