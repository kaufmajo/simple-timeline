<?php

declare(strict_types=1);

namespace App\Form;

use interop\container\containerinterface;
use Laminas\Form\Form;

class LoginFormFactory extends Form
{
    /**
     * @return LoginForm
     */
    public function __invoke(containerinterface $container)
    {
        $form = new LoginForm();
        $form->init();

        return $form;
    }
}
