<?php

namespace Cpm\JovenesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cpm_jovenes');

		$rootNode
			->children()
				->booleanNode('bloquear_ciclos_viejos')->end()
				->booleanNode('bloquear_registro_usuarios')->end()

			->end()
        ;
        /*
         * 				->arrayNode('etapas')
                	->prototype('array')
	                	->children()
		                	->scalarNode('nombre')->isRequired()->end()
		             		->arrayNode('accionesUsuario')->useAttributeAsKey('name')
		             			->prototype('array')
									->children()
										->scalarNode('href')->defaultNull()->end()
										->scalarNode('label')->isRequired()->end()
										->scalarNode('condition')->defaultNull()->end()
										->booleanNode('enabled')->defaultTrue()->end()
									->end()
		             			->end()->children()->scalarNode('patOh')->defaultNull()->end()->end()
		             		->end()
			             	->arrayNode('accionesProyecto')->useAttributeAsKey('name')
		             			->prototype('array')
									->children()
										->scalarNode('href')->defaultNull()->end()
										->scalarNode('label')->isRequired()->end()
										->scalarNode('condition')->defaultNull()->end()
										->booleanNode('enabled')->defaultTrue()->end()
									->end()
		             			->end()->children()->scalarNode('path')->defaultNull()->end()->end()
		             		->end()
		    			->end()
		    		->end()
	           ->end()
         */

        return $treeBuilder;
    }
}
