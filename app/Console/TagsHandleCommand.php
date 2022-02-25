<?php

namespace App\Console\Commands;

use App\Jobs\TagsHandleJob;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Console\Command;

class TagsHandleCommand extends Command
{
    protected $signature = 'tags:handle';

    protected $description = 'Handle Tags Command';

    public function handle(): void
    {
        $this->info('Processing all user Tag Handlers...');

        User::active()->chunkById(100, function ($users) {
            dispatch(new TagsHandleJob($users));
        });

        $this->info('Done!');
    }
}
