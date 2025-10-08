<?php

namespace App\Support\Traits;

trait HasHighlightTitle
{
    /**
     * Highlight the title based on the search query.
     */
    public function highlightTitle(?string $keyword, string $attribute = 'title'): string
    {
        if (empty($keyword)) {
            return $this->getAttribute($attribute);
        }

        return preg_replace_callback(
            '/'.preg_quote($keyword, '/').'/i',
            function ($matches) {
                return '<span class="highlight">'.$matches[0].'</span>';
            },
            $this->getAttribute($attribute),
        );
    }
}
