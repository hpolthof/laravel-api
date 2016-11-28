<?php namespace Hpolthof\LaravelAPI\Exceptions;

use Illuminate\Http\Response;

class NotImplementedException extends HttpStatusException
{
    protected $code = Response::HTTP_NOT_IMPLEMENTED;
}