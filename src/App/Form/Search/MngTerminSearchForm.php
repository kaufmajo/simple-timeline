<?php

declare(strict_types=1);

namespace App\Form\Search;

use App\Form\Element\Select\TerminAnsichtElementSelect;
use App\Form\Element\Select\TerminKategorieElementSelect;
use App\Form\Element\Select\TerminStatusElementSelect;
use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;

class MngTerminSearchForm extends Form implements InputFilterProviderInterface
{
    public function init()
    {
        $this->setName('termin_mng_search_form');

        $this->add(
            [
                'name'       => 'search_start',
                'type'       => Element\Date::class,
                'options'    => [
                    'label'            => 'Von',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-search-start',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'search_ende',
                'type'       => Element\Date::class,
                'options'    => [
                    'label'            => 'Bis',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-search-ende',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'search_suchtext',
                'type'       => Element\Search::class,
                'options'    => [
                    'label'            => 'Suchbegriff',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-search-suchtext',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'search_kategorie',
                'type'       => TerminKategorieElementSelect::class,
                'options'    => [
                    'label'            => 'Kategorie',
                    'label_attributes' => [],
                    //'empty_option' => 'Kategorie',
                ],
                'attributes' => [
                    'id'       => 'select-search-kategorie',
                    'multiple' => 'multiple',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'search_ansicht',
                'type'       => TerminAnsichtElementSelect::class,
                'options'    => [
                    'label'            => 'Ansicht',
                    'label_attributes' => [],
                    //'empty_option' => '',
                ],
                'attributes' => [
                    'id'       => 'select-search-ansicht',
                    'multiple' => 'multiple',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'search_status',
                'type'       => TerminStatusElementSelect::class,
                'options'    => [
                    'label'            => 'Status',
                    'label_attributes' => [],
                    //'empty_option' => 'Status',
                ],
                'attributes' => [
                    'id'       => 'select-search-status',
                    'multiple' => 'multiple',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'search_tage',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Tage',
                    'label_attributes' => [],
                    'value_options'    => ['1' => 'Montag', '2' => 'Dienstag', '3' => 'Mittwoch', '4' => 'Donnerstag', '5' => 'Freitag', '6' => 'Samstag', '7' => 'Sonntag'],
                ],
                'attributes' => [
                    'id'       => 'select-search-tage',
                    'multiple' => 'multiple',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'search_anzeige',
                'type'       => Element\Checkbox::class,
                'options'    => [
                    'label'            => 'Termintage anzeigen?',
                    'label_attributes' => [],
                    'checked_value'    => '1',
                    'unchecked_value'  => '0',
                ],
                'attributes' => [
                    'id' => 'input-search-anzeige',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'submit-button',
                'type'       => Element\Submit::class,
                'attributes' => [
                    'id'    => 'input-search-submit',
                    'value' => 'Suchen',
                ],
            ]
        );
    }

    public function getInputFilterSpecification()
    {
        return [
            'search_start'     => [
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
            ],
            'search_ende'      => [
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
                ],
            ],
            'search_suchtext'  => [
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ],
            'search_kategorie' => [
                'required'   => false,
                'filters'    => [],
                'validators' => [],
            ],
            'search_ansicht'   => [
                'required'   => true,
                'filters'    => [],
                'validators' => [],
            ],
            'search_status'    => [
                'required'   => true,
                'filters'    => [],
                'validators' => [],
            ],
            'search_tage'      => [
                'required'   => true,
                'filters'    => [],
                'validators' => [],
            ],
            'search_anzeige'   => [
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\ToInt::class],
                ],
                'validators' => [],
            ],
        ];
    }
}
