<?php

namespace MaxMind\MinFraud\Exception;

/**
 * Thrown when the MaxMind minFraud service returns an error.
 */
class InvalidRequestException extends HttpException
{
    /**
     * The code returned by the MaxMind web service
     */
    public $error;

    /**
     * @param string $message
     * @param int $error
     * @param int $httpStatus
     * @param string $uri
     * @param \Exception $previous
     */
    public function __construct(
        $message,
        $error,
        $httpStatus,
        $uri,
        \Exception $previous = null
    ) {
        $this->error = $error;
        parent::__construct($message, $httpStatus, $uri, $previous);
    }
}
