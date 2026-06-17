<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpirePropertyEntries extends Command
{
    protected $signature   = 'property:expire';
    protected $description = 'Deprecated — expires_at concept has been removed';

    public function handle(): void
    {
        $this->info('Command is a no-op: expires_at has been removed from PropertyEntry.');
    }
}
