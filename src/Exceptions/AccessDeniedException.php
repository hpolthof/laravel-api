<?php namespace Hpolthof\LaravelAPI\Exceptions;


class AccessDeniedException extends HttpStatusException
{
    protected $code = 403;
}