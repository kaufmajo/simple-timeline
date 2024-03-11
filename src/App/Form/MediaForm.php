<?php

declare(strict_types=1);

namespace App\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class MediaForm extends Form
{
    public function init()
    {
        $this->setName('media_form');

        $this->add([
            'name'       => 'media_datei',
            'type'       => Element\File::class,
            'attributes' => [
                'id' => 'media-datei-input',
            ],
            'options'    => [
                'label' => 'Datei',
            ],
        ]);

        $this->add(
            [
                'name'       => 'media_id',
                'type'       => Element\Hidden::class,
                'options'    => [
                    'label'            => 'ID',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-id',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_groesse',
                'type'       => Element\Hidden::class,
                'options'    => [
                    'label'            => 'Groesse',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-groesse',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_mimetype',
                'type'       => Element\Hidden::class,
                'options'    => [
                    'label'            => 'MimeType',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-mimetype',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_hash',
                'type'       => Element\Hidden::class,
                'options'    => [
                    'label'            => 'Hash',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-hash',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_anzeige',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Anzeige',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-anzeige',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_von',
                'type'       => Element\Date::class,
                'options'    => [
                    'label'            => 'Von',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-von',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_bis',
                'type'       => Element\Date::class,
                'options'    => [
                    'label'            => 'Bis',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-bis',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_tag',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Tag',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-media-tag',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_privat',
                'type'       => Element\Checkbox::class,
                'options'    => [
                    'label'            => 'Dieses Media-Element ist privat?',
                    'label_attributes' => [],
                    'checked_value'    => '1',
                    'unchecked_value'  => '0',
                ],
                'attributes' => [
                    'id' => 'input-media-privat',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'submit',
                'type'       => Element\Submit::class,
                'attributes' => [
                    'value' => 'Speichern',
                ],
            ]
        );
    }
}
