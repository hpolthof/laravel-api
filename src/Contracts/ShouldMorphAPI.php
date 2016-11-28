<?php namespace Hpolthof\LaravelAPI\Contracts;

use Hpolthof\LaravelAPI\Binding;

interface ShouldMorphAPI
{
    /**
     * @return Binding
     */
    public function bindAPI();
}