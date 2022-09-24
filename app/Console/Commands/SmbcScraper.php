<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SmbcScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:smbc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client(["verify" => false]);
        $url = "https://www.smbc-comics.com";

        for($i=1;$i<10;$i++) {
            if(Cache::has('smbc-'. $url)){
                $html = Cache::get('smbc-'. $url);
            }
            else
            {
                $response = $client->get($url);
                $html = $response->getBody()->getContents();
                Cache::put('smbc-'. $url, $html);
            }

            $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
            $imgEl = $crawler->filter('#cc-comicbody img');
            $link = $crawler->filter('#navtop .cc-prev');
            $url = $link->attr('href');
            var_dump($imgEl->attr('src'));
        }
    }
}
