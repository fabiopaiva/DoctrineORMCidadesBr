<?php

namespace DoctrineORMCidadesBr\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineORMCidadesBr\Entity\Endereco as EnderecoEntity;
use DoctrineORMCidadesBr\Entity\Cidade as CidadeEntity;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

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

        $cidade = new Fieldset("cidade");
        $cidade->setObject(new CidadeEntity())
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
                            'name' => "nome",
                            'options' => array(
                                'label' => 'Cidade'
                            ),
                            'attributes' => array(
                                'data-source' => '/cidades/buscar-cidade',
                                'data-id' => "{$name}_cidade",
                                'class' => 'form-control input-sm doctrine_orm_cidades_br_autocomplete input_cidade_label',
                                'id' => "label_{$name}_cidade",
                                'attributes' => "style='width:200px'"
                            )
                        )
                )
        ;

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
                        'style' => 'width:90px; margin-right:10px',
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
                        'style' => 'width:230px; margin-right:10px'
                    )
                ))
                ->add(array(
                    'name' => 'numero',
                    'options' => array(
                        'label' => 'Número'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm input_numero',
                        'style' => 'width:60px; margin-right:10px'
                    )
                ))
                ->add(array(
                    'name' => 'complemento',
                    'options' => array(
                        'label' => 'Complemento'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm input_complemento',
                        'style' => 'width:70px; margin-right:10px'
                    )
                ))
                ->add(array(
                    'name' => 'bairro',
                    'options' => array(
                        'label' => 'Bairro'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm input_bairro',
			'style' => 'width:180px; margin-right:10px;'
                    )
                ))
                ->add($cidade)
                ->add(array(
                    'name' => 'id',
                    'type' => 'hidden'
                ))
        ;
        /* @var $request Zend\Http\PhpEnvironment\Request */
        $request = $this->sm->get('Request');
        if ($request->isPost()) {
            $file = $request->getFiles()->get($name);
            if ($file && is_file($file['file']['tmp_name'])) {
                $params = $file['file'];
                $newName = $destinationFolder
                        . DIRECTORY_SEPARATOR
                        . uniqid()
                        . '_'
                        . $params['name'];
                move_uploaded_file($params['tmp_name'], $newName);
                /* @var $newParams \Zend\Stdlib\Parameters */
                $newParams = $request->getPost();
                $newParams->set($name, array(
                    'name' => $params['name'],
                    'type' => $params['type'],
                    'size' => $params['size'],
                    'filepath' => $newName
                ));
            }
        }
    }

    public function getInputFilterSpecification() {
        return array();
    }

}
