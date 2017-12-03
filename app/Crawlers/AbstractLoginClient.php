<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 22:37
 */
namespace App\Crawlers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

/**
 * Class AbstractLoginClient
 * @package App\Crawlers
 */
abstract class AbstractLoginClient
{
    /**
     * @return string
     */
    abstract protected function getLoginUrl(): string;

    /**
     * @param Crawler $crawler
     * @return Form
     */
    abstract protected function logic(Crawler $crawler): Form;

    /**
     * @var array
     */
    protected $cookie;

    /**
     * @var array
     */
    protected $user;

    /**
     * @var Client
     */
    protected $client;

    /**
     * LoggedInClient constructor.
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if (is_null($this->cookie)) {
            $this->cookie = $this->getLoggedInClient()
                ->getCookieJar()
                ->all();
        }
        $client = new Client();
        $client->getCookieJar()->updateFromSetCookie($this->cookie);
        return $client;
    }

    /**
     * @return Client
     */
    protected function getLoggedInClient(): Client
    {
        $client = new Client();
        $crawler = $client->request('GET', $this->getLoginUrl());
        $form = $this->logic($crawler);
        $client->submit($form);
        return $client;
    }

    /**
     * @return array
     */
    protected function getUser(): array
    {
        return $this->user;
    }
}