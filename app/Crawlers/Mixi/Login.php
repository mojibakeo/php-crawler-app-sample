<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 22:51
 */
namespace App\Crawlers\Mixi;

use App\Crawlers\AbstractLoginClient;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

/**
 * Class Login
 * @package App\Crawlers\Mixi
 */
class Login extends AbstractLoginClient
{
    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return 'https://mixi.jp/';
    }

    /**
     * @param Crawler $crawler
     * @return Form
     */
    protected function logic(Crawler $crawler): Form
    {
        $form = $crawler->filter('form[name="login_form"]')->form();
        $form['email'] = $this->user['email'];
        $form['password'] = $this->user['password'];
        return $form;
    }
}