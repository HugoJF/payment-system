<?php

namespace App\Services\Forms;

use Kris\LaravelFormBuilder\FormBuilder;

class BaseForm
{
    protected $builder;

    public function __construct()
    {
        $this->builder = app(FormBuilder::class);
    }
}
