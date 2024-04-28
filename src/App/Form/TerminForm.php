<?php

declare(strict_types=1);

namespace App\Form;

use App\Enum\TerminStatusEnum;
use App\Form\Element\Select\TerminAnsichtElementSelect;
use App\Form\Element\Select\TerminStatusElementSelect;
use Laminas\Form\Element;
use Laminas\Form\Form;

use function in_array;

class TerminForm extends Form
{
    public function init(): void
    {
        $this->setName('termin_form');

        $this->add(
            [
                'name'       => 'multi_select',
                'type'       => Element\Hidden::class,
                'options'    => [
                    'label'            => 'Multi-Select',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-multi-select',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_id',
                'type'       => Element\Hidden::class,
                'options'    => [
                    'label'            => 'ID',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-id',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_status',
                'type'       => TerminStatusElementSelect::class,
                'options'    => [
                    'label'            => 'Status',
                    'label_attributes' => [],
                    'empty_option'     => '',
                ],
                'attributes' => [
                    'id' => 'select-termin-status',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_datum_start',
                'type'       => Element\Date::class,
                'options'    => [
                    'label'            => 'Datum von',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-datum-start',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_datum_ende',
                'type'       => Element\Date::class,
                'options'    => [
                    'label'            => 'Datum bis',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-datum-ende',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeit_start',
                'type'       => Element\Time::class,
                'options'    => [
                    'label'            => 'Uhrzeit von',
                    'label_attributes' => [],
                    'format'           => 'H:i',
                ],
                'attributes' => [
                    'id'   => 'input-termin-zeit-start',
                    'min'  => '00:00',
                    'max'  => '23:59',
                    'step' => '60',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeit_ende',
                'type'       => Element\Time::class,
                'options'    => [
                    'label'            => 'Uhrzeit bis',
                    'label_attributes' => [],
                    'format'           => 'H:i',
                ],
                'attributes' => [
                    'id'   => 'input-termin-zeit-ende',
                    'min'  => '00:00',
                    'max'  => '23:59',
                    'step' => '60',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_betreff',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Anzeigetext',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-betreff',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_text',
                'type'       => Element\Textarea::class,
                'options'    => [
                    'label'            => 'Text',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-text',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_kategorie',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Kategorie',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-kategorie',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_mitvon',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Mit',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-mitvon',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_image',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Bild',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-image',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Link (Url)',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-link',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link_titel',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Link (Titel)',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-link-titel',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link2',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Link2 (Url)',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-link2',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_link2_titel',
                'type'       => Element\Text::class,
                'options'    => [
                    'label'            => 'Link2 (Titel)',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-link2-titel',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_serie_intervall',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Häufigkeit',
                    'label_attributes' => [],
                    'value_options'    => [
                        'weekly' => 'Wöchentlich',
                        'monthly' => 'Monatlich (1)',
                        'monthly2' => 'Monatlich (2)'
                    ],
                    'empty_option'     => '',
                ],
                'attributes' => [
                    'id' => 'select-termin-serie-intervall',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_serie_wiederholung',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Wiederholung',
                    'label_attributes' => [],
                    'value_options'    => [
                        ['label' => '', 'value' => ''],
                        ['label' => 'Jede Woche', 'value' => '1 week', 'attributes' => ['data-intervall' => 'weekly']],
                        ['label' => 'Jede zweite Woche', 'value' => '2 weeks', 'attributes' => ['data-intervall' => 'weekly']],
                        ['label' => 'Jede dritte Woche', 'value' => '3 weeks', 'attributes' => ['data-intervall' => 'weekly']],
                        ['label' => 'Jede vierte Woche', 'value' => '4 weeks', 'attributes' => ['data-intervall' => 'weekly']],
                        ['label' => 'Am ersten Termintag jeden Monats', 'value' => 'first [day] of next month', 'attributes' => ['data-intervall' => 'monthly']],
                        ['label' => 'Am zweiten Termintag jeden Monats', 'value' => 'second [day] of next month', 'attributes' => ['data-intervall' => 'monthly']],
                        ['label' => 'Am dritten Termintag jeden Monats', 'value' => 'third [day] of next month', 'attributes' => ['data-intervall' => 'monthly']],
                        ['label' => 'Am vierten Termintag jeden Monats', 'value' => 'fourth [day] of next month', 'attributes' => ['data-intervall' => 'monthly']],
                        ['label' => 'Am letzten Termintag jeden Monats', 'value' => 'last [day] of next month', 'attributes' => ['data-intervall' => 'monthly']],
                        ['label' => 'Am ersten Termintag jeden zweiten Monats', 'value' => 'first [day] of +2 month', 'attributes' => ['data-intervall' => 'monthly2']],
                        ['label' => 'Am zweiten Termintag jeden zweiten Monats', 'value' => 'second [day] of +2 month', 'attributes' => ['data-intervall' => 'monthly2']],
                        ['label' => 'Am dritten Termintag jeden zweiten Monats', 'value' => 'third [day] of +2 month', 'attributes' => ['data-intervall' => 'monthly2']],
                        ['label' => 'Am vierten Termintag jeden zweiten Monats', 'value' => 'fourth [day] of +2 month', 'attributes' => ['data-intervall' => 'monthly2']],
                        ['label' => 'Am letzten Termintag jeden zweiten Monats', 'value' => 'last [day] of +2 month', 'attributes' => ['data-intervall' => 'monthly2']],
                    ],
                ],
                'attributes' => [
                    'id' => 'select-termin-serie-wiederholung',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_serie_ende',
                'type'       => Element\Date::class,
                'options'    => [
                    'label'            => 'Serienende',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-serie-ende',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeit_ganztags',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Ganztagstermin',
                    'label_attributes' => [],
                    'value_options'    => [
                        ['label' => '', 'value' => ''],
                        ['label' => 'Ja', 'value' => '1'],
                        ['label' => 'Nein', 'value' => '0'],
                    ],
                ],
                'attributes' => [
                    'id' => 'input-termin-zeit-ganztags',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeige_konflikt',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Konflikte für diesen Termin anzeigen?',
                    'label_attributes' => [],
                    'value_options'    => [
                        ['label' => '', 'value' => ''],
                        ['label' => 'Ja', 'value' => '1'],
                        ['label' => 'Nein', 'value' => '0'],
                    ],
                ],
                'attributes' => [
                    'id' => 'input-termin-zeige-konflikt',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_aktiviere_drucken',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Diesen Termin für den Ausdruck einbeziehen?',
                    'label_attributes' => [],
                    'value_options'    => [
                        ['label' => '', 'value' => ''],
                        ['label' => 'Ja', 'value' => '1'],
                        ['label' => 'Nein', 'value' => '0'],
                    ],
                ],
                'attributes' => [
                    'id' => 'input-termin-aktiviere-drucken',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_ansicht',
                'type'       => TerminAnsichtElementSelect::class,
                'options'    => [
                    'label'            => 'Ansicht',
                    'label_attributes' => [],
                    'empty_option'     => '',
                ],
                'attributes' => [
                    'id' => 'select-termin-ansicht',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_ist_konfliktrelevant',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Diesen Termin für die Konfliktberechnung einbeziehen?',
                    'label_attributes' => [],
                    'value_options'    => [
                        ['label' => '', 'value' => ''],
                        ['label' => 'Ja', 'value' => '1'],
                        ['label' => 'Nein', 'value' => '0'],
                    ],
                ],
                'attributes' => [
                    'id' => 'input-termin-ist-konfliktrelevant',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeige_einmalig',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Nur einmalig diesen Termin anzeigen?',
                    'label_attributes' => [],
                    'value_options'    => [
                        ['label' => '', 'value' => ''],
                        ['label' => 'Ja', 'value' => '1'],
                        ['label' => 'Nein', 'value' => '0'],
                    ],
                ],
                'attributes' => [
                    'id' => 'input-termin-zeige-einmalig',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_zeige_tagezuvor',
                'type'       => Element\Select::class,
                'options'    => [
                    'label'            => 'Erst anzeigen',
                    'label_attributes' => [],
                    'value_options'    => [
                        ['label' => '', 'value' => ''],
                        ['label' => '1 Tag zuvor', 'value' => '1'],
                        ['label' => '7 Tage zuvor', 'value' => '7'],
                        ['label' => '14 Tage zuvor', 'value' => '14'],
                        ['label' => '21 Tage zuvor', 'value' => '21'],
                        ['label' => '30 Tage zuvor', 'value' => '30'],
                        ['label' => '60 Tage zuvor', 'value' => '60'],
                        ['label' => '90 Tage zuvor', 'value' => '90'],
                    ],
                ],
                'attributes' => [
                    'id' => 'input-termin-zeige-tagezuvor',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'termin_notiz',
                'type'       => Element\Textarea::class,
                'options'    => [
                    'label'            => 'Notizen',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-termin-notiz',
                ],
            ]
        );

        $this->add([
            'name'       => 'media_datei_link',
            'type'       => Element\File::class,
            'attributes' => [
                'id' => 'media-datei-link',
            ],
            'options'    => [
                'label' => 'Datei',
            ],
        ]);

        $this->add([
            'name'       => 'media_datei_link2',
            'type'       => Element\File::class,
            'attributes' => [
                'id' => 'media-datei-link2',
            ],
            'options'    => [
                'label' => 'Datei',
            ],
        ]);

        $this->add([
            'name'       => 'media_datei_bild',
            'type'       => Element\File::class,
            'attributes' => [
                'id' => 'media-datei-bild',
            ],
            'options'    => [
                'label' => 'Datei',
            ],
        ]);

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

    public function isValid(): bool
    {
        // init
        $inputFilter = $this->getInputFilter();

        // serie - set required true if ...
        if (null === $this->getValidationGroup() || in_array('termin_serie_intervall', $this->getValidationGroup())) {
            $intervallElement = $this->get('termin_serie_intervall');

            if ('' !== $intervallElement->getValue()) {
                $inputFilter->get('termin_serie_wiederholung')->setRequired(true);
                $inputFilter->get('termin_serie_ende')->setRequired(true);
            }
        }

        // mitvon - set required if status is set to "warnung"
        if (null === $this->getValidationGroup() || in_array('termin_status', $this->getValidationGroup())) {
            $statusElement = $this->get('termin_status');

            if (TerminStatusEnum::WARNUNG->value === $statusElement->getValue()) {
                $inputFilter->get('termin_mitvon')->setRequired(true);
            }
        }

        //        // zeitEnde - set required true if ...
        //        $zeitStartElement = $this->get('termin_zeit_start');
        //
        //        if ('' != $zeitStartElement->getValue())
        //        {
        //            $inputFilter->get('termin_zeit_ende')->setRequired(true);
        //        }

        return parent::isValid(); // TODO: Change the autogenerated stub
    }
}
