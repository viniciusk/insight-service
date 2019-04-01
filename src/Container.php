<?php

namespace InsightService;


use InsightService\Controller\Response;
use InsightService\Error\ErrorHandler;
use InsightService\Error\ErrorHandlerInterface;

/**
 * Class Container
 * @package InsightService
 */
class Container
{
    /**
     * @var ErrorHandlerInterface $errorHandler
     */
    private $errorHandler;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @return ErrorHandlerInterface
     */
    public function getErrorHandler(): ErrorHandlerInterface
    {
        if ($this->errorHandler === null) {
            $this->errorHandler = new ErrorHandler();
        }

        return $this->errorHandler;
    }

    /**
     * @param bool $outputHeaders
     * @return Response
     */
    public function getResponse(bool $outputHeaders = true): Response
    {
        if ($this->response === null) {
            $this->response = new Response($outputHeaders);
        }

        return $this->response;
    }
}
