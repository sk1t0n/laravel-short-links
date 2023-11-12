<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkRequest;
use App\Services\LinkService;
use App\Services\TokenService;
use App\Services\ZookeeperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

class LinkController extends Controller
{
    public function create(
        LinkRequest $request,
        LinkService $linkService,
        ZookeeperService $zookeeperService,
        TokenService $tokenService
    ): JsonResponse {
        $validated = $request->validated();
        $fullUrl = $validated['full_url'];
        if (Cache::has($fullUrl)) {
            return response()->json(['token' => Cache::get($fullUrl)], 200);
        }

        $link = $linkService->getLinkByFullUrl($fullUrl);
        if ($link) {
            return response()->json(['token' => $link->token], 200);
        }

        $range = $zookeeperService->getRange(1);
        $lastNumberFromRange = $zookeeperService->getLastNumberFromRange($range, 1);
        $token = $tokenService->createToken($lastNumberFromRange);
        $linkService->createLink($token, $fullUrl);

        return response()->json(['token' => $token], 201);
    }

    public function redirect(
        LinkService $linkService,
        string $token
    ): RedirectResponse|JsonResponse {
        $link = $linkService->getLinkByToken($token);

        if (! $link) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return Redirect::away($link->full_url, 302);
    }
}
