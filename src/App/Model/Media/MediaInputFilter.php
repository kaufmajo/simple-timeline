<?php

declare(strict_types=1);

namespace App\Model\Media;

use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laminas\Filter;
use Laminas\InputFilter\FileInput;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

use function getcwd;

class MediaInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'       => 'media_datei',
                'type'       => FileInput::class,
                'required'   => true,
                'filters'    => [
                    [
                        'name'    => Filter\File\RenameUpload::class,
                        'options' => [
                            'target'              => getcwd() . '/data/temp',
                            'useUploadName'       => true,
                            'useUploadExtension'  => true,
                            'overwrite'           => true,
                            'randomize'           => true,
                            'stream_factory'      => new StreamFactory(),
                            'upload_file_factory' => new UploadedFileFactory(),
                        ],
                    ],
                ],
                'validators' => [
                    [
                        'name'    => Validator\File\Extension::class,
                        'options' => [
                            'extension' => 'doc,docx,jpg,jpeg,odt,pdf,png,ppt,pptx,txt,xls,xlsx,zip',
                        ],
                    ],
                    [
                        'name'    => Validator\File\Size::class,
                        'options' => [
                            'max' => '10MB',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_id',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\ToNull::class],
                    ['name' => Filter\ToInt::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_groesse',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\ToNull::class],
                    ['name' => Filter\ToInt::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_mimetype',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_hash',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_anzeige',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_von',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\Date::class,
                        'options' => [
                            'format' => 'Y-m-d',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_bis',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\Date::class,
                        'options' => [
                            'format' => 'Y-m-d',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'media_hash',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_tag',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_privat',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\ToInt::class],
                ],
                'validators' => [],
            ]
        );

    }
}
