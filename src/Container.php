<?php

namespace InsightService;


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
     * @var GenericService $genericService
    private $genericService;
     */

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

    /*
    /**
     * @return GenericService
    public function getGenericService(): GenericService
    {
        if ($this->genericService === null) {
            $this->genericService = new GenericService($this->getErrorHandler(), new UserRepository($this->getRepositoryHandler()));
        }

        return $this->genericService;
    }
    */
}
