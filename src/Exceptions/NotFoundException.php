<?php namespace Hpolthof\LaravelAPI\Exceptions;

class NotFoundException extends HttpStatusException
{
    protected $code = 404;
}