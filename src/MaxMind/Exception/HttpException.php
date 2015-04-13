<?php

namespace MaxMind\Exception;

/**
 *  Thrown on an HTTP transport error.
 */
class HttpException extends WebServiceException
{
    /**
     * The URI queried
     */
    public $uri;

    /**
     * @param string $message
     * @param int $httpStatus
     * @param string $uri
     * @param \Exception $previous
     */
    public function __construct(
        $message,
        $httpStatus,
        $uri,
        \Exception $previous = null
    ) {
        $this->uri = $uri;
        parent::__construct($message, $httpStatus, $previous);
    }
}
