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
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Short Links API", version="1.0")
 *
 * @OA\Tag(name="token")
 * @OA\Tag(name="redirect")
 */
class LinkController extends Controller
{
    /**
     * @OA\Post(
     *     tags={"token"},
     *     path="/api/v1/create",
     *     summary="Create an token by the full URL.",
     *
     * @OA\Parameter(
     *
     *         @OA\Schema(type="string", default=""),
     *         name="full_url",
     *         in="query",
     *         required=true,
     *         description="The full URL"
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="CREATED"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     tags={"redirect"},
     *     path="/{token}",
     *     summary="Redirect to the full URL.",
     *
     *     @OA\Parameter(
     *
     *         @OA\Schema(type="string", default=""),
     *         name="token",
     *         in="path",
     *         required=true,
     *         description="The token"
     *     ),
     *
     *     @OA\Response(
     *         response="302",
     *         description="FOUND"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="NOT FOUND"
     *     )
     * )
     */
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
