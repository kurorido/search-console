<?php

require __DIR__.'/../vendor/autoload.php';

use Carbon\Carbon;
use RoliChung\SearchConsole\SearchConsoleRankingsJob;

$today = Carbon::now();
$siteUrl = 'https://www.canfly.com.tw/';
$keyPath = __DIR__ . '/../key.json';
$data = (new SearchConsoleRankingsJob($today, $siteUrl, $keyPath))->run();
print_r($data);
