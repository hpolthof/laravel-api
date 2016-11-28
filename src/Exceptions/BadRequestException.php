<?php namespace Hpolthof\LaravelAPI\Exceptions;

class BadRequestException extends HttpStatusException
{
    protected $code = 400;
}