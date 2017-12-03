<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 23:25
 */
namespace App\Services\Mixi;

use App\Crawlers\Mixi\Login;
use App\Crawlers\Mixi\My;

/**
 * Class FetchRecentTimeLine
 * @package App\Services\Mixi
 */
class FetchRecentTimeLine
{
    /**
     * @var array
     */
    protected $user;

    /**
     * FetchRecentTimeLine constructor.
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function fetch(): array
    {
        $login = new Login($this->user);
        $my = new My($login);
        return $my->crawl();
    }
}