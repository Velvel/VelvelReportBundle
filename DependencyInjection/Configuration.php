<?php
/**
 * This file is part of VelvelReportBundle (C) 2012 Velvel IT Solutions
 *
 * VelvelReportBundle is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License
 * as published by the Free Software Foundation, either version 3
 * of the License, or (at your option) any later version.
 *
 * VelvelReportBundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General
 * Public License along with VelvelReportBundle. If not,
 * see <http://www.gnu.org/licenses/>.
 */

namespace Velvel\ReportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * DIC Configuration
 *
 * @author r1pp3rj4ck <attila.bukor@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('velvel_report', 'array');

        $rootNode
            ->children()
                ->arrayNode('reports')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->scalarNode('class')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('templates')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('show')->defaultValue('VelvelReportBundle::show.html.twig')->cannotBeEmpty()->end()
                        ->scalarNode('list')->defaultValue('VelvelReportBundle::list.html.twig')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}