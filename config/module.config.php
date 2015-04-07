<?php

return array(
    'doctrine-orm-cidades-br' => array(
        'webserviceCep' => 'http://cep.sigweb.net.br/%s'
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/DoctrineORMCidadesBr/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'DoctrineORMCidadesBr\Entity' => 'application_entities'
                )
            ))),
    'router' => array(
        'routes' => array(
            'cidades' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/cidades[/:action][/:id]',
                    'defaults' => array(
                        'controller' => 'DoctrineORMCidadesBr\Controller\Cidade',
                        'action' => 'index',
                    ),
                ),
            ),
            'estados' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/estados[/:action][/:id]',
                    'defaults' => array(
                        'controller' => 'DoctrineORMCidadesBr\Controller\Uf',
                        'action' => 'index',
                    ),
                ),
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'DoctrineORMCidadesBr\Controller\Cidade' => 'DoctrineORMCidadesBr\Controller\CidadeController',
            'DoctrineORMCidadesBr\Controller\Uf' => 'DoctrineORMCidadesBr\Controller\UfController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'DoctrineORMCidadesBr' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
