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
     * @var bool $outputHeaders
     */
    private $outputHeaders;

    /**
     * @var bool determines whether the response is successful
     */
    protected $success = false;

    /**
     * @var bool whether the (occasional) error is transient or permanent
     */
    protected $transientError = false;

    /**
     * @var array $payload a set of information to follow with the response
     */
    protected $payload = [];

    /**
     * Response constructor.
     * @param bool $outputHeaders
     */
    public function __construct(bool $outputHeaders = true)
    {
        $this->outputHeaders = $outputHeaders;
    }

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
     * @param bool $isError
     */
    public function setStatus(string $status, bool $isError = false): void
    {
        $this->status = $status;
        if (true === $isError) {
            $this->addError($status);
        }
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
        if (!empty($responseArray['errors'])) {
            $responseArray['transient'] = $this->transientError;
        }
        $responseArray['payload'] = $this->getPayload();
        return $responseArray;
    }


    /**
     * @return void
     */
    public function finish(): void
    {
        if (true === $this->outputHeaders) {
            $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
            header("{$serverProtocol} {$this->getStatus()}");
            header('Content-Type: application/json');
        }
        echo json_encode($this->getResponseArray());
        if (true === $this->outputHeaders) { // needed for phpunit tests for now
            exit(0);
        }
    }

}
