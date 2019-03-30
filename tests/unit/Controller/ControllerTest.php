<?php

use \PHPUnit\Framework\TestCase;
use \InsightService\Container;
use \InsightService\Controller\Controller;

class ControllerTest extends TestCase
{
    /**
     * @var \InsightService\Controller\Controller $controller
     */
    protected $controller;

    /**
     * @var \InsightService\Container $container
     */
    protected $container;

    public function setUp(): void
    {
        $this->container = new Container();
        $this->controller = new Controller($this->container, ['route'=>'user', 'action'=>'isRegistered']);
    }

    public function testControllerInstance(): void
    {
        $this->assertInstanceOf(\InsightService\Controller\Controller::class, $this->controller);
    }

    public function testGetResponse(): void
    {
        $this->assertInstanceOf(\InsightService\Controller\Response::class, $this->controller->getResponse());
    }

    public function testControllerNotFound(): void
    {
        $this->controller = new InsightService\Controller\Controller($this->container, ['route' => 'notnotnot']);
        $this->assertEquals(\InsightService\Controller\Response::RESPONSE_404_NOT_FOUND, $this->controller->getResponse()->getStatus());
    }

    /*public function testGetEffectiveController(): void
    {
        $controller = new InsightService\Controller\Controller($this->container, ['route'=>'', 'action'=>'isRegistered']);
        $this->assertInstanceOf(\InsightService\Controller\Controller::class, $controller->getEffectiveController());
    }*/

    public function testControllerRoute(): void
    {
        $this->assertEquals('user', $this->controller->getRoute());
    }

    public function testControllerAction(): void
    {
        $this->assertEquals('isRegistered', $this->controller->getAction());
    }

    public function testControllerWithServerUri(): void
    {
        $_SERVER['REQUEST_URI'] = '/user/isRegistered/test';
        $this->controller = new InsightService\Controller\Controller($this->container);
        $this->assertEquals('user', $this->controller->getRoute());
        $this->assertEquals('isRegistered', $this->controller->getAction());
    }

    public function testActionNotFound(): void
    {
        $controller = new Controller($this->container, ['route'=>'user', 'action'=>'notnotnot']);
        $controller->run(true);
        $this->assertEquals(\InsightService\Controller\Response::RESPONSE_404_NOT_FOUND, $controller->getResponse()->getStatus());
    }

    public function testFinishWithInvalidRequestError(): void
    {
        $this->controller->finishWithInvalidRequestError(true);
        $this->assertEquals(\InsightService\Controller\Response::RESPONSE_400_BAD_REQUEST, $this->controller->getResponse()->getStatus());
        $this->assertEquals(\InsightService\Error\ErrorMessagesInterface::INVALID_REQUEST, $this->controller->getResponse()->getLastError());
    }
}
