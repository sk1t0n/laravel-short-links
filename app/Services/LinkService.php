<?php

namespace App\Services;

use App\Models\Link;
use App\Repositories\LinkRepository;
use Illuminate\Support\Facades\Cache;

class LinkService
{
    public function __construct(private LinkRepository $linkRepository)
    {
    }

    public function getLinkByFullUrl(string $url): ?Link
    {
        return $this->linkRepository->findByFullUrl($url);
    }

    public function getLinkByToken(string $token): ?Link
    {
        return $this->linkRepository->findByToken($token);
    }

    public function createLink(string $token, string $fullUrl): void
    {
        Link::create([
            'token' => $token,
            'full_url' => $fullUrl,
        ]);
        Cache::set($fullUrl, $token);
    }
}
