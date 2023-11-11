<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

class LinkController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $fullUrl = $request->json()->get('full_url');
        if (Cache::has($fullUrl)) {
            return response()->json(['token' => Cache::get($fullUrl)], 200);
        }

        $link = Link::where(['full_url' => $fullUrl])->first();
        if ($link) {
            return response()->json(['token' => $link->token], 200);
        }

        $rangeReceivedFromZookeeper = [1, 1000];
        Cache::add('lastNumberFromRangeForNode1', $rangeReceivedFromZookeeper[0] - 1);
        Cache::increment('lastNumberFromRangeForNode1');
        $lastNumberFromRangeForNode1 = (int) Cache::get('lastNumberFromRangeForNode1');

        $token = (new TokenService())->createToken($lastNumberFromRangeForNode1);
        Cache::set($fullUrl, $token);
        Link::create([
            'token' => $token,
            'full_url' => $fullUrl,
        ]);

        return response()->json(['token' => $token], 201);
    }

    public function redirect(string $token): RedirectResponse|JsonResponse
    {
        $link = Link::where(['token' => $token])->first();

        if (! $link) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return Redirect::away($link->full_url, 302);
    }
}
