<?php

declare(strict_types=1);

namespace App\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class LoginForm extends Form
{
    public function init()
    {
        $this->setName('login_form');

        $this->add(
            [
                'name'       => 'user',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'username',
                    'label_attributes' => [],
                ],
                'attributes' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'password',
                'type'       => Element\Password::class,
                'options'    => [
                    'label'            => 'password',
                    'label_attributes' => [],
                ],
                'attributes' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'submit',
                'type'       => Element\Submit::class,
                'attributes' => [
                    'value' => 'login',
                ],
            ]
        );
    }
}
