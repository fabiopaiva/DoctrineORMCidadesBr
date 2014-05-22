DoctrineORMCidadesBr
====================

Oferece entidades, dados e um CRUD para edição de cidades do Brasil

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



Use as rotas

	<?php echo $this->url('cidades');?> para cidades
	<?php echo $this->url('estados');?> para estados

Além de um CRUD para gerenciar os dados você pode usar as entidades do repositório
	DoctrineORMCidadesBr\Entity\Cidade
	DoctrineORMCidadesBr\Entity\Uf
	DoctrineORMCidadesBr\Entity\Endereco

## Contato
paiva.fabiofelipe@gmail.com

