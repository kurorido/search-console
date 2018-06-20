<?php

namespace RoliChung\SearchConsole;

class SearchConsole
{
    protected $client;
    protected $service;

    public function __construct($key_path)
    {
        $this->client = new \Google_Client();
        $this->client->setAuthConfig($key_path);
        $this->client->addScope(\Google_Service_Webmasters::WEBMASTERS_READONLY);
        $this->service = new \Google_Service_Webmasters($this->client);
    }

    public function sites()
    {
        return $this->service->sites->listSites();
    }

    public function query($site_url, $startDate, $endDate, $page = 1)
    {
        $request = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dimensions' => [
                'query',
                'page'
            ],
             // 0 ~ 999, 1000 ~ 1999, 2000 ~ 2999...
            'startRow' => ($page - 1) * 1000,
            'rowLimit' => 999
        ]);

        $results = $this->service->searchanalytics->query($site_url, $request);

        return $results;
    }

    public function isExist($site_url, $startDate, $endDate)
    {
        try {
            $this->query($site_url, $startDate, $endDate);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


}