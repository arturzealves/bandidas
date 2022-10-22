<?php

namespace App\Http\Controllers;

use App\Observers\CustomCrawlerObserver;
use Exception;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Weidner\Goutte\GoutteFacade as Goutte;
use Spekulatius\SpatieCrawlerToolkit\Queues\CacheCrawlQueue;
use Spekulatius\SpatieCrawlerToolkit\Observers\CrawlLogger;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $url = 'https://www.lipsum.com';

        try {
            //# initiate crawler 
            $crawler = Crawler::create([RequestOptions::ALLOW_REDIRECTS => true, RequestOptions::TIMEOUT => 30])
                ->acceptNofollowLinks()
                ->ignoreRobots()
                ->setParseableMimeTypes(['text/html'])
                ->setCrawlObserver(new CustomCrawlerObserver())
                // ->setCrawlObserver(new CrawlLogger())
                ->setCrawlProfile(new CrawlInternalUrls($url))
                ->setCrawlQueue(new CacheCrawlQueue($url))
                ->setMaximumResponseSize(1024 * 1024 * 2) // 2 MB maximum
                ->setTotalCrawlLimit(100) // limit defines the maximal count of URLs to crawl
                // ->setConcurrency(1) // all urls will be crawled one by one
                ->setDelayBetweenRequests(100);
            
            $crawler->startCrawling('https://www.lipsum.com');

            $queue = $crawler->getCrawlQueue();
            if ($queue->hasPendingUrls()) {
                dump($queue->getProcessedUrlCount(). ' :pending: ' . $queue->getPendingUrl());
            } else {
                dump('nothing pending');
            }
            return true;


            // https://symfony.com/doc/current/components/browser_kit.html

            // $crawler = Goutte::request('GET', 'https://duckduckgo.com/html/?q=Laravel');
            // dump($crawler);
            // $crawler->filter('.result__title .result__a')->each(function ($node) {
            //     dump($node->text());
            // });
        } catch (Exception $e) {
            dump($e->getMessage());
        }

        return view('welcome');
    }
}
