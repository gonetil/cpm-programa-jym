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
				->arrayNode('etapas')
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
		             			->end()->children()->scalarNode('patOh')->defaultNull()->end()->end()
		             		->end()
		    			->end()
		    		->end()
	           ->end()
			->end()
        ;
/*
array(1) {
  ["etapas"]=>
  array(1) {
    ["etapa1"]=>
    array(2) {
      ["accionesUsuario"]=>
      array(1) {
        ["action1"]=>
        array(4) {
          ["path"]=>
          string(9) "/action-1"
          ["label"]=>
          string(10) "Accionar 1"
          ["condition"]=>
          string(19) "user.name == 'pepe'"
          ["enabled"]=>
          bool(false)
        }
      }
      ["accionesProyecto"]=>
      array(0) {
      }
    }
  }
}
*/
        return $treeBuilder;
    }
}
