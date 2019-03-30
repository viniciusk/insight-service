<?php
require 'bootstrap.php';

$container = new \InsightService\Container();
$controller = new \InsightService\Controller\Controller($container, $_REQUEST ?? null);
$controller->run();
exit;
