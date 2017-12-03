<?php
/**
 * Created by PhpStorm.
 * User: bko
 * Date: 2017/12/03
 * Time: 23:18
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\Mixi\FetchRecentTimeLine;

if (PHP_SAPI != "cli") {
    say("cli からつかってね");
    exit;
}

if ($argc < 2) {
    say("ログインメールアドレスとパスワードを引数に指定してね");
    exit;
}

list($script, $email, $password) = $argv;
$service = new FetchRecentTimeLine([
    'email' => $email,
    'password' => $password,
]);
$timeLine = $service->fetch();

print_r($timeLine);

/**
 * @param string $msg
 * @param bool $lineBreak
 */
function say(string $msg, bool $lineBreak = true): void
{
    echo sprintf("%s%s", $msg, $lineBreak ? "\n" : "");
}
