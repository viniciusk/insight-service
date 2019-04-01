<?php

namespace InsightService\Controller;


use InsightService\Container;
use InsightService\Error\ErrorMessagesInterface;

/**
 * Class Controller
 * @package InsightService\Controller
 */
class Controller
{
    /**
     * @var array $params
     */
    private $params;

    /**
     * @var string $route
     */
    private $route;

    /**
     * @var string $action
     */
    private $action;

    /**
     * @var Container $container
     */
    private $container;

    /**
     * @var ContextControllerInterface $controller the context controller
     */
    private $controller;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * Controller constructor.
     * @param Container $container
     * @param Response $response
     * @param array|null $params
     */
    public function __construct(Container $container, Response $response, ?array $params = null)
    {
        $this->container = $container;
        $this->response = $response;
        $this->resolveParams($params);
        if (null === $this->getRoute()) {
            $this->finishWithInvalidRequestError();
        }
        $routeController = "{$this->getRoute()}Controller";
        if (method_exists($this, $routeController)) {
            $this->controller = $this->$routeController();
            return;
        }
        $this->response->setStatus(Response::RESPONSE_404_NOT_FOUND, true);
        $this->finish();
    }

    /**
     * @return ContextControllerInterface
     */
    public function getEffectiveController(): ContextControllerInterface
    {
        return $this->controller;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param null|array $params
     */
    private function resolveParams(?array $params = null): void
    {
        $this->params = $params;
        if (!empty($this->params['route'])) {
            $this->route = $this->params['route'];
        } else {
            $this->route = $this->getURIParam(1);
        }
        if (!empty($this->params['action'])) {
            $this->action = $this->params['action'];
        } else {
            $this->action = $this->getURIParam(2);
        }
    }

    /**
     * @return null|string
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @return null|string
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @param int $position
     * @return null|string
     */
    private function getURIParam(int $position): ?string
    {
        $requestURIArray = explode('?', $_SERVER['REQUEST_URI'] ?? '');
        $requestURIArray = explode('/', $requestURIArray[0] ?? '');
        return $requestURIArray[$position] ?? null;
    }

    /**
     * Runs the expected action
     */
    public function run(): void
    {
        $action = $this->getAction();
        if (method_exists($this->controller, $action)) {
            $this->controller->$action();
            $this->finish();
        }
        $this->response->setStatus(Response::RESPONSE_404_NOT_FOUND, true);
        $this->finish();
    }

    /**
     * Terminates the execution through the Response
     */
    public function finish(): void
    {
        $this->response->finish();
    }

    /**
     * Adds an error and terminates the execution
     */
    public function finishWithInvalidRequestError(): void
    {
        $this->response->setStatus(Response::RESPONSE_400_BAD_REQUEST, true);
        $this->response->addError(ErrorMessagesInterface::INVALID_REQUEST);
        $this->finish();
    }

    /**
     * @return ContextControllerInterface
     */
    private function statusController(): ContextControllerInterface
    {
        return new StatusController($this);
    }

}
