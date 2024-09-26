<?php

namespace App\Searches;


class MovieSearchParams
{
//    private ?string $title = null;
//
//    public function __construct(array $params)
//    {
//        $this->title = $params['title'] ?? null;
//    }

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
