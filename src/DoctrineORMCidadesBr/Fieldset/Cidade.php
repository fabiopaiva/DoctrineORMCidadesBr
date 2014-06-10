<?php

namespace DoctrineORMCidadesBr\Fieldset;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineORMCidadesBr\Entity\Cidade as CidadeEntity;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class Cidade extends Fieldset implements InputFilterProviderInterface {

    /**
     * \Zend\ServiceManager\ServiceManager
     * @var 
     */
    private $sm;

    /**
     * Name of the field is required
     * @param string $name
     * @param \Zend\ServiceManager\ServiceManager $sm
     * @param string $destinationFolder
     */
    public function __construct($name, \Zend\ServiceManager\ServiceManager $sm, $label = 'Cidade') {
        $this->sm = $sm;
        parent::__construct($name);
        $this->setObject(new CidadeEntity())
                ->setHydrator(new DoctrineObject($sm->get('Doctrine\ORM\EntityManager')))
                ->add(
                        array(
                            'type' => 'hidden',
                            'name' => "id",
                            'attributes' => array(
                                'type' => 'hidden',
                                'id' => "{$name}_cidade",
                                "class" => "input_cidade"
                            )
                        )
                )
                ->add(
                        array(
                            'name' => "label",
                            'options' => array(
                                'label' => $label
                            ),
                            'attributes' => array(
                                'data-source' => '/cidades/buscar-cidade',
                                'data-id' => "{$name}_cidade",
                                'class' => 'form-control input-sm doctrine_orm_cidades_br_autocomplete input_cidade_label',
                                'id' => "label_{$name}_cidade",
                            )
                        )
                )
        ;
    }

    public function getInputFilterSpecification() {
        return array();
    }

}
