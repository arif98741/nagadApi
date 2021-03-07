<?php


namespace NagadApi;


class ExceptionHandler extends \Exception
{
    /**
     * Generate Custom Exception
     * @return string
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