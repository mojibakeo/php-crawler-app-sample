<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 23:18
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\Mixi\FetchNews;

if (PHP_SAPI != "cli") {
    say("cli からつかってね");
    exit;
}

$service = new FetchNews();
$news = $service->fetch();

print_r($news);

/**
 * @param string $msg
 * @param bool $lineBreak
 */
function say(string $msg, bool $lineBreak = true): void
{
    echo sprintf("%s%s", $msg, $lineBreak ? "\n" : "");
}
