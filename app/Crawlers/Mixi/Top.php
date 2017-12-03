<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/04
 * Time: 0:22
 */
namespace App\Crawlers\Mixi;

use App\Crawlers\AbstractCrawler;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Top
 * @package App\Crawlers\Mixi
 */
class Top extends AbstractCrawler
{
    /**
     * @return string
     */
    protected function getUrl(): string
    {
        return 'https://mixi.jp';
    }

    /**
     * @param array $parameters
     * @return array
     */
    protected function logic(array $parameters): array
    {
        $crawler = $this->createCrawler($parameters);
        $newsListNodes = $crawler->filter('.newsList li');
        if ($newsListNodes->count() === 0) {
            return [];
        }
        $news = [];
        $newsListNodes->each(function (Crawler $newListNode) use (&$news) {
            $linkNodes = $newListNode->filter('a');
            if ($linkNodes->count() === 0) {
                return;
            }
            $linkNode = $linkNodes->first();
            $title = trim($linkNode->text());
            $url = $linkNode->attr('href');
            $news[] = compact('title', 'url');
        });
        return $news;
    }
}