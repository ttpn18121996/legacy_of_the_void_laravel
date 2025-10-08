<?php

namespace App\Console\Commands;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Facades\Log;

abstract class Command extends BaseCommand
{
    public function log($message, ?string $level = null)
    {
        Log::log($level ?? 'info', $message);
        $this->line($message, $level);
    }

    public function logInfo($message)
    {
        $this->log($message, 'info');
    }

    public function logError($message)
    {
        $this->log($message, 'error');
    }
}
