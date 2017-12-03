<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 22:37
 */
namespace App\Crawlers;

use Goutte\Client;

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
     * @return Client
     */
    abstract protected function getLoggedInClient(): Client;

    /**
     * @var array
     */
    protected $cookie;

    /**
     * @var array
     */
    protected $user;

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
     * @return array
     */
    protected function getUser(): array
    {
        return $this->user;
    }
}