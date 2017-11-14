<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class DefaultRepository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
}   