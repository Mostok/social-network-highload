<?php
require __DIR__.'/vendor/autoload.php';

use Phpmig\Adapter;
use Src\Components\DataBase;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$container = new ArrayObject();

$container['db'] = (new DataBase())->link;
$container['phpmig.adapter'] = new Adapter\PDO\Sql($container['db'], 'migrations');

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

// You can also provide an array of migration files
// $container['phpmig.migrations'] = array_merge(
//     glob('migrations_1/*.php'),
//     glob('migrations_2/*.php')
// );

return $container;