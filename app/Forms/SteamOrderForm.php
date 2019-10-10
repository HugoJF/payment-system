<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class SteamOrderForm extends Form
{
    public function buildForm()
    {
        $this->add('tradeoffer_state', 'select');
    }
}
