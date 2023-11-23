<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ZookeeperService
{
    /**
     * @return array<int, int>
     */
    public function getRange(int $nodeNumber): array
    {
        // Todo: change to Zookeeper
        return [1, 1000];
    }

    /**
     * @param array<int, int> $range
     */
    public function getLastNumberFromRange(array $range, int $nodeNumber): int
    {
        Cache::add("lastNumberFromRangeForNode{$nodeNumber}", $range[0] - 1);
        Cache::increment("lastNumberFromRangeForNode{$nodeNumber}");

        return (int) Cache::get("lastNumberFromRangeForNode{$nodeNumber}");
    }
}
