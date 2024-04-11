<?php
require_once 'functions.php';
require_once __DIR__ . '/../libraries/Psr4AutoloaderClass.php';
$loader = new Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('CT275\Project', __DIR__ .'/classes');
require_once 'functions.php';
    $loader->addNamespace('CT275\Project', __DIR__ . '/classes');
try {
    $PDO = (new CT275\Project\PDOFactory())->create([
        'dbhost' => 'localhost',
        'dbname' => 'ct275_project',
        'dbuser' => 'root',
    'dbpass' => ''
    ]);
} catch (Exception $ex) {
    echo 'Không thể kết nối đến MySQL, kiểm tra lại username/password đến MySQL.<br>';
    exit("<pre>${ex}</pre>");
}
