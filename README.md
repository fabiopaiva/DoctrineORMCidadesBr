DoctrineORMCidadesBr
====================

Oferece entidades, dados, formulario de endereço e um CRUD para edição de cidades do Brasil

## Instalação

	php composer.phar require fabiopaiva/doctrine-orm-cidades-br:dev-master

## Uso

Habilite os módulos no application.config.php:

	<?php //
		return array(
    			'modules' => array(
				'DoctrineModule',
				'DoctrineORMModule',
				'DoctrineORMCidadesBr',
				// .. Outros módulos
				'Application'
				 ),
				...

Configure o Doctrine (atenção para o charset utf8)
ex: doctrine.local.php

	<?php
		return array(
		    'doctrine' => array(
		        'connection' => array(
		            'orm_default' => array(
		                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
		                'params' => array(
                		    'host' => 'localhost',
		                    'port' => '3306',
                		    'user' => 'dbUser',
		                    'password' => 'dbPass',
		                    'dbname' => 'dbName',
				    'charset' => 'utf8'
		        )))));


Crie as tabelas no banco de dados

	vendor/bin/doctrine-module orm:schema-tool:update --dump-sql
	#se o comando acima exibir o Create Table para cidade, uf e endereço prossiga
	vendor/bin/doctrine-module orm:schema-tool:update --force
	unzip vendor/fabiopaiva/doctrine-orm-cidades-dr/data/cidades.zip
	vendor/bin/doctrine-module dbal:import cidades.sql
	rm cidades.sql

## Helpers

Use as rotas

	<?php echo $this->url('cidades');?> para cidades
	<?php echo $this->url('estados');?> para estados

Além de um CRUD para gerenciar os dados você pode usar as entidades do repositório

	DoctrineORMCidadesBr\Entity\Cidade
	DoctrineORMCidadesBr\Entity\Uf
	DoctrineORMCidadesBr\Entity\Endereco

## Relacionamento

Adicione o relacionamento em sua entidade 

    /**
     * @ORM\ManyToOne(targetEntity="DoctrineORMCidadesBr\Entity\Endereco", cascade={"persist","remove"})
     * @var \DoctrineORMCidadesBr\Entity\Endereco
     */
    protected $endereco;

## Formulário

    $form = new Form(); // Zend Form
    $endereco = new EnderecoForm('endereco', $serviceLocator);
    $endereco->setLabel('Endereço');
    $form->add($endereco);

Adicione os scripts para buscar cidade no formulário (Autocomplete) 

    //cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js
    //code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css

Adicione o código

    $(document).ready(function(){ doctrineOrmCidadesBrAutoComplete(); });
    function doctrineOrmCidadesBrAutoComplete(){$(".doctrine_orm_cidades_br_autocomplete").each(function(){var e=$(this).data("id");var t={};var n=$(this).data("source");$(this).autocomplete({source:function(e,r){var i=e.term;if(i in t){r(t[i]);return}$.getJSON(n,e,function(e,n,s){if(e.length==0){e.push({value:0,label:"Nada encontrado"})}t[i]=e;r(e)})},minLength:2,change:function(t,n){if(n.item){if(n.item.value==0&&n.item.label=="Nada encontrado"){n.item=null}}if(n.item==null){modalNotify("Selecione um item na lista","A cidade deve ser selecionada na lista");$("#"+e).val("");$(this).val("");return false}},select:function(t,n){if(n.item.value==0&&n.item.label=="Nada encontrado"){n.item=null;$("#"+e).val("");$(this).val("");modalNotify("Selecione um item na lista","A cidade deve ser selecionada na lista")}$("#"+e).val(n.item.value);$(this).val(n.item.label);return false}})})}

A busca pelo CEP usa o webservice http://cep.sigweb.net.br para utilizar outro Webservice sobrescreva:

    return array(
        'doctrine-orm-cidades-br' => array(
            'webserviceCep' => 'http://meuwebservice/%s'
        ),
    );

## Contato
paiva.fabiofelipe@gmail.com
