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
     * @param array $parameters
     * @return array
     */
    abstract protected function logic(array $parameters): array;

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
     * @var int
     */
    protected $current;

    /**
     * AbstractCrawler constructor.
     * @param AbstractLoginClient|null $client
     */
    public function __construct(AbstractLoginClient $client = null)
    {
        $goutte = new Client();
        if (is_subclass_of($client, AbstractLoginClient::class)) {
            $goutte = $client->getClient();
        }
        $this->client = $goutte;
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function crawl(array $parameters = []): array
    {
        if ($this->hasPaginator === false) {
            return $this->logic($parameters);
        }
        $values = [];
        $this->current = $this->paginationStart;
        while (true) {
            $values[] = array_merge($values, $this->logic($parameters));
            if ($this->hasNext() === false) {
                break;
            }
            $this->current++;
        }
        return $values;
    }

    /**
     * @param array $parameters
     * @return Crawler
     */
    protected function createCrawler(array $parameters = []): Crawler
    {
        if ($this->hasPaginator) {
            $parameters = array_merge([
                $this->paginationParamName => $this->current,
            ], $parameters);
        }
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