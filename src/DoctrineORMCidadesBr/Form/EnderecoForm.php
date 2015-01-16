<?php

namespace DoctrineORMCidadesBr\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineORMCidadesBr\Entity\Endereco as EnderecoEntity;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMCidadesBr\Fieldset\Cidade as CidadeFieldset;

class EnderecoForm extends Fieldset implements InputFilterProviderInterface {

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
    public function __construct($name, \Zend\ServiceManager\ServiceManager $sm) {
        $this->sm = $sm;
        parent::__construct($name);
        $this->setAttribute('class', 'fieldset-endereco');


        $cidade = new CidadeFieldset("cidade", $sm);

        $this
                ->setObject(new EnderecoEntity())
                ->setHydrator(new DoctrineObject($sm->get('Doctrine\ORM\EntityManager')))
                ->add(array(
                    'name' => 'cep',
                    'options' => array(
                        'label' => 'CEP'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm mask input_cep',
                        'data-mask' => '99.999-999',
                        'onchange' => "if(jQuery){var cxCep = this; jQuery.getJSON("
                        . "'/cidades/buscarCep/' + this.value, "
                        . "function(d){"
                        . "if(d.status != 'sucesso')return;"
                        . "jQuery(cxCep.form).find('input.input_logradouro:first').val(d.tp_logradouro + ' ' + d.logradouro);"
                        . "jQuery(cxCep.form).find('input.input_bairro:first').val(d.bairro);"
                        . "jQuery(cxCep.form).find('input.input_cidade_label:first').val(d.cidade);"
                        . "jQuery(cxCep.form).find('input.input_cidade:first').val(d.idCidade);"
                        . "});}"
                    )
                ))
                ->add(array(
                    'name' => 'logradouro',
                    'options' => array(
                        'label' => 'Logradouro'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm input_logradouro',
                    )
                ))
                ->add(array(
                    'name' => 'numero',
                    'options' => array(
                        'label' => 'Número'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm input_numero',
                    )
                ))
                ->add(array(
                    'name' => 'complemento',
                    'options' => array(
                        'label' => 'Complemento'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm input_complemento',
                    )
                ))
                ->add(array(
                    'name' => 'bairro',
                    'options' => array(
                        'label' => 'Bairro'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm input_bairro',
                    )
                ))
                ->add($cidade)
                ->add(array(
                    'name' => 'id',
                    'type' => 'hidden'
                ))
        ;
    }

    public function getInputFilterSpecification() {
        return array();
    }

}
