<?php

namespace App\Repositories;

use App\Models\Link;

class LinkRepository
{
    public function findByToken(string $token): ?Link
    {
        return Link::where(['token' => $token])->first();
    }

    public function findByFullUrl(string $url): ?Link
    {
        return Link::where(['full_url' => $url])->first();
    }
}
