<?php

namespace DoctrineORMCidadesBr\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMCidadesBr\Entity\Cidade;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;

class CidadeController extends AbstractActionController {

    public function indexAction() {

        $query = $this
                ->getOM()
                ->getRepository('DoctrineORMCidadesBr\Entity\Cidade')
                ->createQueryBuilder('q')
        ;
        $term = '';
        if ($this->getRequest()->isPost()) {
            $term = $this->params()->fromPost('pesquisa');
            $query->where('q.nome LIKE :pesquisa')
                    ->setParameter('pesquisa', "%{$term}%");
        }
        $paginator = new Paginator(
                new DoctrinePaginator(new ORMPaginator($query))
        );
        $paginator
                ->setCurrentPageNumber($this->params()->fromQuery('page', 1))
                ->setItemCountPerPage(20);

        return array(
            'term' => $term,
            'paginator' => $paginator,
        );
    }

    public function novoAction() {
        $form = $this->getForm();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $cidade = $form->getData();
                $this->getOM()->persist($cidade);
                $this->getOM()->flush();
                $this->flashMessenger()->addSuccessMessage('Cidade inserida');
                $this->redirect()->toRoute('cidades');
            }
        }
        $form->prepare();

        return array(
            'form' => $form
        );
    }

    public function editarAction() {

        $id = $this->params()->fromRoute('id');
        $tipo = $this
                ->getOM()
                ->getRepository('DoctrineORMCidadesBr\Entity\Cidade')
                ->find($id);
        $form = $this->getForm();
        $form->bind($tipo);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $this->getOM()->persist($form->getData());
                $this->getOM()->flush();
                $this->flashMessenger()->addSuccessMessage('Registro atualizado');
                $this->redirect()->toRoute('cidades');
            }
        }
        $form->prepare();

        return array(
            'form' => $form
        );
    }

    public function excluirAction() {
        try {
            $id = $this->params()->fromRoute('id');
            $entity = $this
                    ->getOM()
                    ->getRepository('DoctrineORMCidadesBr\Entity\Cidade')
                    ->find($id);
            $this->getOM()->remove($entity);
            $this->getOM()->flush();
            $this->flashMessenger()->addSuccessMessage('Registro removido');
            $this->redirect()->toRoute('cidades');
        } catch (\Doctrine\DBAL\DBALException $exc) {
            $this->flashMessenger()->addErrorMessage('Ocorreu um erro ao tentar '
                    . 'apagar o registro. Verifique se existem registros dependentes.');
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            $this->redirect()->toRoute('cidades');
        } catch (\Exception $exc) {
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            $this->redirect()->toRoute('cidades');
        }
    }

    private function getForm() {
        $form = new Form('tipo');
        $form
                ->setAttribute('class', 'form-horizontal')
                ->setHydrator(new DoctrineEntity($this->getOM(), 'DoctrineORMCidadesBr\Entity\Cidade'))
                ->setObject(new Cidade())
                ->add(array(
                    'name' => 'nome',
                    'options' => array(
                        'label' => 'Nome:'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm',
                        'size' => 50
                    )
                ))
                ->add(array(
                    'name' => 'label',
                    'options' => array(
                        'label' => 'Label:'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm',
                        'size' => 50
                    )
                ))
                ->add(array(
                    'name' => 'uf',
                    'type' => 'DoctrineORMModule\Form\Element\EntitySelect',
                    'options' => array(
                        'label' => 'Estado',
                        'object_manager' => $this->getOM(),
                        'target_class' => 'DoctrineORMCidadesBr\Entity\Uf',
                        'property' => 'nome'
                    ),
                    'attributes' => array(
                        'required' => true,
                        'class' => 'form-control input-sm'
                    )
                ))
                ->add(array(
                    'name' => 'salvar',
                    'type' => 'submit',
                    'attributes' => array(
                        'value' => 'Salvar',
                        'class' => 'btn btn-sm btn-success'
                    )
        ));
        return $form;
    }

    public function buscarCidadeAction() {
        $term = $this->params()->fromQuery('term');
        $cidades = $this
                ->getOM()
                ->getRepository('DoctrineORMCidadesBr\Entity\Cidade')
                ->createQueryBuilder('q')
                ->where('q.nome like :term ')
                ->setParameter('term', "%{$term}%")
                ->getQuery()
                ->execute()
        ;
        $lista = array();
        foreach ($cidades as $cidade) {
            $lista[] = array(
                'value' => $cidade->getId(),
                'label' => $cidade->getLabel()
            );
        }

        return new JsonModel($lista);
    }
    
    public function buscarCepAction() {
        $config = $this->getServiceLocator()->get('Config');
        $webservice = $config['doctrine-orm-cidades-br']['webserviceCep'];
        $cep = $this->params()->fromRoute('id');
        $resposta = json_decode(file_get_contents(sprintf($webservice, $cep)), true);
        return new JsonModel($resposta);
        
    }

    /**
     * ORM object manager
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getOM() {
        return $this
                        ->getServiceLocator()
                        ->get('Doctrine\ORM\EntityManager');
    }

}
