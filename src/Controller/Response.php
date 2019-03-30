<?php

namespace InsightService\Controller;

use InsightService\Error\ErrorHandlerTrait;

/**
 * Class Response
 * @package InsightService\Controller
 */
class Response
{
    use ErrorHandlerTrait;

    public const RESPONSE_200_OK = '200 OK';
    public const RESPONSE_400_BAD_REQUEST = '400 Bad Request';
    public const RESPONSE_404_NOT_FOUND = '404 Not Found';

    /**
     * @var string $status
     */
    private $status;
    /**
     * @var bool determines whether the response is successful
     */
    protected $success = false;
    /**
     * @var array $payload a set of information to follow with the response
     */
    protected $payload = [];


    /**
     * @param string $node
     * @param $value
     */
    public function setNode(string $node, $value): void
    {
        $this->payload[$node] = $value;
    }


    /**
     * @param string $node
     * @return mixed|null
     */
    public function getNode(string $node)
    {
        return $this->payload[$node] ?? null;
    }


    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }


    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


    /**
     * @return string
     */
    public function getStatus(): string
    {
        if (null !== $this->status) {
            return $this->status;
        }
        return !$this->hasError() ? self::RESPONSE_200_OK : self::RESPONSE_400_BAD_REQUEST;
    }


    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        $responseArray = [];
        $responseArray['success'] = !$this->hasError();
        $responseArray['errors'] = $this->getErrors();
        $responseArray['payload'] = $this->getPayload();
        return $responseArray;
    }


    /**
     * @param bool $outputBodyOnly
     * @return void
     */
    public function finish(bool $outputBodyOnly = false): void
    {
        if (!$outputBodyOnly) {
            $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
            header("{$serverProtocol} {$this->getStatus()}");
            header('Content-Type: application/json');
        }
        echo json_encode($this->getResponseArray());
        if (!$outputBodyOnly) {
            exit(0);
        }
    }

}
