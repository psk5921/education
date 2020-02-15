<?php


namespace app\common\exception;


use think\Exception;

class DiyException  extends Exception
{
    private $statusCode;
    private $headers;
    protected $message;

    public function __construct($statusCode, $message = null, \Exception $previous = null, array $headers = [], $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers    = $headers;
        $this->message    = $message;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
    public function getError()
    {
        return $this->message;
    }
}