DoctrineORMCidadesBr
====================

Oferece entidades, dados e um CRUD para edição de cidades do Brasil

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

Importe os dados do arquivo data/cidades.zip

Use as rotas

	<?php echo $this->url('cidades');?> para cidades
	<?php echo $this->url('estados');?> para estados

Além de um CRUD para gerenciar os dados você pode usar as entidades do repositório

	DoctrineORMCidadesBr\Entity\Cidade
	DoctrineORMCidadesBr\Entity\Uf
	DoctrineORMCidadesBr\Entity\Endereco

## Contato
paiva.fabiofelipe@gmail.com

