<?php

declare(strict_types=1);

namespace App\Model\Termin;

use App\Validator\TerminDateValidator;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laminas\Filter;
use Laminas\InputFilter\FileInput;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

use function getcwd;

class TerminInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'       => 'termin_id',
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
                'name'       => 'termin_status',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\ToNull::class],
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_datum_start',
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
                'name'       => 'termin_datum_ende',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\Date::class,
                        'options' => [
                            'format' => 'Y-m-d',
                        ],
                    ],
                    [
                        'name' => TerminDateValidator::class,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeit_start',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'messages' => ['regexNotMatch' => 'Ungültige Uhrzeit, HH:mm'],
                            'pattern'  => '/^([01]?[0-9]|2[0-3]):[0-5][0-9]/',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeit_ende',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'messages' => ['regexNotMatch' => 'Ungültige Uhrzeit, HH:mm'],
                            'pattern'  => '/^([01]?[0-9]|2[0-3]):[0-5][0-9]/',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_betreff',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_text',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_kategorie',
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_mitvon',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_image',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link_titel',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link2',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link2_titel',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_serie_intervall',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_serie_wiederholung',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\InArray::class,
                        'options' => [
                            'strict'   => Validator\InArray::COMPARE_STRICT,
                            'haystack' => [
                                '1 week',
                                '2 weeks',
                                '3 weeks',
                                '4 weeks',
                                'first [day] of next month',
                                'second [day] of next month',
                                'third [day] of next month',
                                'fourth [day] of next month',
                                'last [day] of next month',
                                'first [day] of +2 month',
                                'second [day] of +2 month',
                                'third [day] of +2 month',
                                'fourth [day] of +2 month',
                                'last [day] of +2 month',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_serie_ende',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
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
                'name'       => 'termin_zeige_tagezuvor',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\ToNull::class],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'messages' => ['regexNotMatch' => 'Ungültige Eingabe'],
                            'pattern'  => '/^(\-1|[0-9]+)$/',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_notiz',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );

        $this->add(
            [
                'name'       => 'media_datei_link',
                'type'       => FileInput::class,
                'required'   => false,
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
                            'extension' => 'doc,docx,jpg,jpeg,pdf,png,ppt,pptx,txt,xls,xlsx,zip',
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
                'name'       => 'media_datei_link2',
                'type'       => FileInput::class,
                'required'   => false,
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
                            'extension' => 'doc,docx,jpg,jpeg,pdf,png,ppt,pptx,txt,xls,xlsx,zip',
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
                'name'       => 'media_datei_bild',
                'type'       => FileInput::class,
                'required'   => false,
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
                            'extension' => 'jpg,jpeg,png',
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
    }
}
