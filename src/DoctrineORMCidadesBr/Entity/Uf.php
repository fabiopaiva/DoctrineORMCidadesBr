<?php

/**
 * Entidade Uf
 *
 * @author fabio
 * @date 07/04/2014
 * @link http://docs.doctrine-project.org/en/2.0.x/reference/basic-mapping.html Data Types
 */

namespace DoctrineORMCidadesBr\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class Uf {

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
    protected $sigla;

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getSigla() {
        return $this->sigla;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function setSigla($sigla) {
        $this->sigla = $sigla;
        return $this;
    }

}
