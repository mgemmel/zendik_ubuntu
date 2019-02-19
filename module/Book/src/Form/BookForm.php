<?php


namespace Book\Form;


use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Text;
use Zend\Form\Element\Date;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;

class BookForm extends Form
{
    public function __construct()
    {
        parent::__construct('addBook');
        $this->addInputFiler();
        $this->addElements();
        $this->setAttributes(array(
            'name' => 'addBook',
            'role' => 'form',
            'method' => 'POST',
            'action' => '/book/add',
        ));
    }

    private function addElements()
    {
        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'nazov',
            'options' => [
                'label' => 'Názov',
            ],
            'attributes' => [
                'id' => 'nazov',
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => 'hidden',
            'name' => 'id'
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'autor',
            'options' => [
                'label' => 'Autor',
            ],
            'attributes' => [
                'id' => 'autor',
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => Date::class,
            'name' => 'datum',
            'options' => [
                'label' => 'Dátum vydania',
            ],
            'attributes' => [
                'id' => 'datum',
                'max' => '2019-01-31',
                'min' => '1900-01-31',
                'value' => '2019-01-14',
                'required' => true
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'popis',
            'options' => [
                'label' => 'Popis',
            ],
            'attributes' => [
                'id' => 'popis',
                'required' => true
            ],
        ]);
        $this->add([
            'type' => Number::class,
            'name' => 'cena',
            'options' => [
                'label' => 'Cena (€)',
            ],
            'attributes' => [
                'id' => 'cena',
                'min' => 0,
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => Select::class,
            'name' => 'kategoria',
            'options' => [
                'label' => 'Kategória',
                'value_options' => array(
                    'Román' => 'Román',
                    'Sci-Fi' => 'Sci-Fi',
                    'Rozprávka' => 'Rozprávka',
                    'Dokument' => 'Dokument',
                )
            ],
            'attributes' => [
                'id' => 'kategoria',
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Uložiť knihu'
            ]
        ]);
    }

    public function addInputFiler()
    {
        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'name' => 'Csrf',
                    'timeout' => 600
                ]
            ]
        ]);
        $inputFilter->add([
            'name' => 'nazov',
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 150
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
                'name' => 'autor',
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 2,
                            'max' => 50
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add([
                'name' => 'datum',
                'validators' => [
                    [
                        'name' => 'Date',
                        'options' => [
                            'max' => '2019-01-31',
                            'min' => '1900-01-31'
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add([
                'name' => 'popis',
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 20,
                            'max' => 500
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add([
                'name' => 'cena',
                'validators' => [
                    [
                        'name' => 'GreaterThan',
                        'options' => [
                            'min' => 0
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add([
            'name' => 'kategoria',
            /*'validators' => [
                [
                    'name' => 'GreaterThan',
                    'options' => [
                        'min' => 0,
                        'max' => 3
                    ],
                ],
            ],*/
        ]);

    }
}