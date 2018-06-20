<?php

namespace RoliChung\SearchConsole;

use Carbon\Carbon;
use RoliChung\SearchConsole\SearchConsole;

class SearchConsoleRankingsJob
{
    protected $date;
    protected $service;
    protected $siteUrl;

    public function __construct(Carbon $date, $siteUrl, $keyPath)
    {
        $this->date = $date;
        $this->service = new SearchConsole($keyPath);
        $this->siteUrl = $siteUrl;
    }

    public function run()
    {
        $data = [];

        $startDate = $this->date;
        $endDate = $startDate->copy()->addDay();
        $startDate = $startDate->tz('America/Dawson_Creek')->toDateString();
        $endDate = $endDate->tz('America/Dawson_Creek')->toDateString();

        $page = 1;
        $count = 0;

        do {
            $response = $this->service->query($this->siteUrl, $startDate, $endDate, $page);
            $changes = $response->getRows();
            $count = count($changes);
            if ($count === 0) {
                break;
            }
            $page = $page + 1;

            foreach($changes as $change)
            {
                $data[] = [
                    'clicks' => $change->clicks,
                    'ctr' => $change->ctr,
                    'keyword' => $change->keys[0],
                    'url' => $change->keys[1],
                    'position' => $change->position,
                    'impressions' => $change->impressions,
                    'date' => $endDate,
                ];
            }
        } while ($count > 0);

        return $data;
    }
}