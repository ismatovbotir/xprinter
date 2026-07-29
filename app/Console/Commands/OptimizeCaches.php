<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeCaches extends Command
{
    protected $signature = 'app:optimize-caches';
    protected $description = 'Cache config, routes, views and warm application cache';

    public function handle(): void
    {
        $this->call('optimize');
        $this->info('All caches warmed successfully.');
    }
}
