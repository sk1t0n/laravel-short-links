<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Facades\Cache;

class LinkService
{
    public function getLinkByFullUrl(string $url): ?Link
    {
        return Link::where(['full_url' => $url])->first();
    }

    public function getLinkByToken(string $token): ?Link
    {
        return Link::where(['token' => $token])->first();
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
