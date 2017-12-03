<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 22:29
 */
namespace App\Crawlers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractCrawler
 * @package App\Crawlers
 */
abstract class AbstractCrawler
{
    /**
     * @return string
     */
    abstract protected function getUrl(): string;

    /**
     * @param Crawler $crawler
     * @return array
     */
    abstract protected function logic(Crawler $crawler): array;

    /**
     * @var string[]
     */
    protected $headers = [
        'User-Agent' =>'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',
    ];

    /**
     * @var AbstractLoginClient|Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected $hasPaginator = false;

    /**
     * @var string
     */
    protected $paginationParamName = 'page';

    /**
     * @var int
     */
    protected $paginationStart = 1;

    /**
     * AbstractCrawler constructor.
     * @param AbstractLoginClient|null $client
     */
    public function __construct(AbstractLoginClient $client = null)
    {
        if (is_null($client)) {
            $client = new Client();
        }
        $this->client = $client;
    }

    /**
     * @param array $parameters
     * @return array
     */
    protected function crawl(array $parameters = []): array
    {
        if ($this->hasPaginator === false) {
            $crawler = $this->createCrawler($parameters);
            return $this->logic($crawler);
        }
        $values = [];
        $current = $this->paginationStart;
        while (true) {
            $crawler = $this->createCrawler(array_merge([
                $this->paginationParamName => $current,
            ], $parameters));
            $values[] = $this->logic($crawler);
            if ($this->hasNext() === false) {
                break;
            }
            $current++;
        }
        return $values;
    }

    /**
     * @param array $parameters
     * @return Crawler
     */
    protected function createCrawler(array $parameters = []): Crawler
    {
        return $this->getSetupClient()->request('GET', $this->getUrl(), $parameters);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @return array
     */
    protected function callApi(string $method, string $url, array $parameters = []): array
    {
        $this->getSetupClient()->request($method, $url, $parameters);
        return json_decode($this->client->getResponse()->getContent(), true) ?? [];
    }

    /**
     * @return Client
     */
    protected function getSetupClient(): Client
    {
        $client = $this->client;
        foreach ($this->headers as $name => $value) {
            $client->setHeader($name, $value);
        }
        return $client;
    }

    /**
     * @param string $name
     * @param string $value
     */
    protected function appendHeaderValue(string $key, string $value): void
    {
        $this->headers[] = [$key => $value];
    }

    /**
     * @return bool
     */
    protected function hasNext(): bool
    {
        return false;
    }
}