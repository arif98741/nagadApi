<?php


namespace NagadApi\Exception;


use Throwable;

class ExceptionHandler extends \Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Generate Custom Exception
     * @return array
     */
    public function generateException()
    {
        return [
            'error' => 'error',
            'message' => $this->getMessage(),
            'line' => $this->getLine(),
            'file' => $this->getFile()
        ];

    }
}