<?php
global $router;
require __DIR__.'/../vendor/autoload.php';

use Laminas\Diactoros\Response\HtmlResponse;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use Src\Components\DataBase;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

session_start();
//$db = (new DataBase())->link;

require_once __DIR__.'/../src/Config/routes.php';

//try {
    $router->dispatch();
//} catch (RouteNotFoundException $e) {
//    // It's 404!
//    $router->getPublisher()->publish(new HtmlResponse('Not found.', 404));
//} catch (Throwable $e) {
//    // Log and report...
//    var_dump($e->getMessage());
//    $router->getPublisher()->publish(new HtmlResponse('Internal error.', 500));
//}
