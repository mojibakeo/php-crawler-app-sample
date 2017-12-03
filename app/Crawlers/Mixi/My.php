<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 22:51
 */
namespace App\Crawlers\Mixi;

use App\Crawlers\AbstractCrawler;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class My
 * @package App\Crawlers\Mixi
 */
class My extends AbstractCrawler
{
    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return 'http://mixi.jp/home.pl';
    }

    /**
     * @param array $parameters
     * @return array
     */
    protected function logic(array $parameters): array
    {
        $crawler = $this->createCrawler($parameters);
        $listNodes = $crawler->filter('.homeFeedList.flow li');
        if ($listNodes->count() === 0) {
            return [];
        }
        $feeds = [];
        $listNodes->each(function (Crawler $listNode) use (&$feeds) {
            $nameNode = $listNode->filter('.name');
            if ($nameNode->count() === 0) {
                return;
            }
            $attributes = [
                'id',
                'type',
                'service',
                'timestamp',
                'owner-id',
                'owner-nickname',
                'owner-thumbnail-url',
            ];
            $feed = [];
            foreach ($attributes as $attribute) {
                $value = $listNode->attr(sprintf('data-%s', $attribute));
                $feed[$attribute] = $value;
            }
            $description = trim($listNode->filter('.description')->first()->html());
            $feeds[] = array_merge($feed, compact('description'));
        });
        return $feeds;
    }
}