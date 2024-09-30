<?php

namespace App\Searches;


class MovieSearchParams
{
    public function __construct(
        private ?string $title = null
    )
    {}

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
