<?php

namespace InsightService\Controller;


class StatusController implements ContextControllerInterface
{
    /**
     * @var Controller $parentController
     */
    private $parentController;

    /**
     * UserController constructor.
     * @param Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->parentController = $controller;
    }

    /**
     * @return Controller
     */
    public function getParentController(): Controller
    {
        return $this->parentController;
    }

    /**
     *
     */
    public function health(): void
    {
        $this->parentController->getResponse()->setNode('live', true);
    }
}
