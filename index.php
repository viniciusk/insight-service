<?php
require 'bootstrap.php';

$container = new \InsightService\Container();
$controller = new \InsightService\Controller\Controller($container, $container->getResponse(), $_REQUEST ?? null);
$controller->run();
exit;
