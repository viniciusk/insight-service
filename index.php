<?php
require 'bootstrap.php';

$container = new \InsightService\Container();
$response = new \InsightService\Controller\Response();
$controller = new \InsightService\Controller\Controller($container, $response, $_REQUEST ?? null);
$controller->run();
exit;
