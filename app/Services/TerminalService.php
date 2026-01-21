<?php

namespace App\Services;

class TerminalService
{
    public function help()
    {
        return [
            'video commands' => [
                'videos:list' => 'List all videos',
                'videos:detail &lt;id&gt;' => 'Show video details',
                'videos:tags &lt;tag1&gt; [&lt;tag2&gt; ...]' => 'List videos with specific tags',
                'videos:find &lt;keyword&gt;' => 'Find videos by video title or actress name',
            ],
            'general' => [
                'refresh' => 'Reload the terminal page',
                'clear|cls' => 'Clear the terminal screen',
                'exit|quit' => 'Exit the terminal session',
                'logout' => 'Log out of the current user session',
            ],
        ];
    }
}
