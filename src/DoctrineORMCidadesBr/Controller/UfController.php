<?php

namespace DoctrineORMCidadesBr\Controller;

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMCidadesBr\Entity\Uf;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class UfController extends AbstractActionController {

    public function indexAction() {

        $query = $this
                ->getOM()
                ->getRepository('DoctrineORMCidadesBr\Entity\Uf')
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
                $estado = $form->getData();
                $this->getOM()->persist($estado);
                $this->getOM()->flush();
                $this->flashMessenger()->addSuccessMessage('Estado inserido');
                $this->redirect()->toRoute('estados');
            }
        }
        $form->prepare();

        return array(
            'form' => $form
        );
    }

    public function editarAction() {

        $id = $this->params()->fromRoute('id');
        $estado = $this
                ->getOM()
                ->getRepository('DoctrineORMCidadesBr\Entity\Uf')
                ->find($id);
        $form = $this->getForm();
        $form->bind($estado);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $this->getOM()->persist($form->getData());
                $this->getOM()->flush();
                $this->flashMessenger()->addSuccessMessage('Registro atualizado');
                $this->redirect()->toRoute('estados');
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
                    ->getRepository('DoctrineORMCidadesBr\Entity\Uf')
                    ->find($id);
            $this->getOM()->remove($entity);
            $this->getOM()->flush();
            $this->flashMessenger()->addSuccessMessage('Registro removido');
            $this->redirect()->toRoute('estados');
        } catch (\Doctrine\DBAL\DBALException $exc) {
            $this->flashMessenger()->addErrorMessage('Ocorreu um erro ao tentar '
                    . 'apagar o registro. Verifique se existem registros dependentes.');
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            $this->redirect()->toRoute('estados');
        } catch (\Exception $exc) {
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
            $this->redirect()->toRoute('estados');
        }
    }

    private function getForm() {
        $form = new Form('tipo');
        $form
                ->setAttribute('class', 'form-horizontal')
                ->setHydrator(new DoctrineEntity($this->getOM(), 'DoctrineORMCidadesBr\Entity\Uf'))
                ->setObject(new Uf())
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
                    'name' => 'sigla',
                    'options' => array(
                        'label' => 'Sigla:'
                    ),
                    'attributes' => array(
                        'class' => 'form-control input-sm',
                        'size' => 50
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
