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

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * DIC extension
 *
 * @author r1pp3rj4ck <attila.bukor@gmail.com>
 */
class VelvelReportExtension extends Extension
{
    /**
     * Loads configurations
     *
     * @param array                                                   $configs   Configurations
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container Container
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('form.xml');
        $loader->load('generator.xml');

        $configuration = new Configuration();
        $processor     = new Processor();
        $config        = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('velvel.report.configuration.reports', $config['reports']);
        $container->setParameter('velvel.report.configuration.show_template', $config['templates']['show']);
        $container->setParameter('velvel.report.configuration.list_template', $config['templates']['list']);
    }
}