<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/04
 * Time: 0:27
 */
namespace App\Services\Mixi;

use App\Crawlers\Mixi\Top;

/**
 * Class FetchNews
 * @package App\Services\Mixi
 */
class FetchNews
{
    public function fetch(): array
    {
        $top = new Top();
        return $top->crawl();
    }
}