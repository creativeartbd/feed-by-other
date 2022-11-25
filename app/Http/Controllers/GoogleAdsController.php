<?php

namespace App\Http\Controllers;
use Edujugon\GoogleAds\GoogleAds;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;

class GoogleAdsController extends Controller
{
    public function callGoogleAds()
    {
        $ads = new GoogleAds();

        $ads->service(CampaignService::class);



        $ads->service(CampaignService::class)
            ->select(['Id', 'Name', 'Status', 'ServingStatus', 'StartDate', 'EndDate'])
            ->get();

        dd($ads);
    }
}
