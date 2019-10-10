<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class MPOrderForm extends Form
{
	public function buildForm()
	{
		$this->add('paid_amount', 'number');
	}
}
