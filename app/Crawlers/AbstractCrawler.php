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
     * @param string $url
     * @param array $parameters
     * @return Crawler
     */
    protected function createCrawler(string $url, array $parameters = []): Crawler
    {
        return $this->getSetupClient()->request('GET', $url, $parameters);
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
}