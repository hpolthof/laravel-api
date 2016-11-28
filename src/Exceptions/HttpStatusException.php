<?php namespace Hpolthof\LaravelAPI\Exceptions;

use Illuminate\Http\Response;

abstract class HttpStatusException extends APIException
{
    protected $code = null;

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if($message == '' || $this->code !== null) {
            $message = Response::$statusTexts[$this->code];
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return integer|null
     */
    public function getStatusCode()
    {
        return $this->code;
    }
}