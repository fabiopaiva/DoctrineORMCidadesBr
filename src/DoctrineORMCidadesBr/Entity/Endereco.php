<?php

/**
 * Entidade Endereco
 *
 * @author fabio
 * @date 22/05/2014
 * @link http://docs.doctrine-project.org/en/2.0.x/reference/basic-mapping.html Data Types
 */

namespace DoctrineORMCidadesBr\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\HasLifecycleCallbacks
 */
class Endereco {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="DoctrineORMCidadesBr\Entity\Cidade")
     * @var Cidade
     */
    protected $cidade;

    /**
     * @ORM\Column(type="string")
     */
    protected $cep;

    /**
     * @ORM\Column(type="string")
     */
    protected $logradouro;

    /**
     * @ORM\Column(type="string")
     */
    protected $numero;

    /**
     * @ORM\Column(type="string")
     */
    protected $complemento;

    /**
     * @ORM\Column(type="string")
     */
    protected $bairro;

    public function getId() {
        return $this->id;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getCep() {
        return $this->cep;
    }

    public function getLogradouro() {
        return $this->logradouro;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCidade(Cidade $cidade = null) {
        $this->cidade = $cidade;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    /**
     * Caso não selecione a cidade, mudar para null
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function verificaCidade() {
        if (!is_null($this->getCidade()) && $this->getCidade()->getId() == '') {
            $this->setCidade(null);
        }
    }

}
