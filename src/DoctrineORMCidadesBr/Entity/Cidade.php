<?php

/**
 * Entidade Cidade
 *
 * @author fabio
 * @date 07/04/2014
 * @link http://docs.doctrine-project.org/en/2.0.x/reference/basic-mapping.html Data Types
 */
namespace DoctrineORMCidadesBr\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Cidade {
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $nome;
    /**
     * @ORM\Column(type="string")
     */
    protected $label;
    /**
     * @ORM\ManyToOne(targetEntity="DoctrineORMCidadesBr\Entity\Uf")
     * @var Uf
     */
    protected $uf;
    
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getUf() {
        return $this->uf;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function setUf(Uf $uf) {
        $this->uf = $uf;
        return $this;
    }
    
    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }


}
