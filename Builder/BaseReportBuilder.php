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

namespace Velvel\ReportBundle\Builder;

use Velvel\ReportBundle\Builder\ReportBuilderInterface;

/**
 * Base report builder class to be extended for every report
 *
 * @author r1pp3rj4ck <attila.bukor@gmail.com>
 */
abstract class BaseReportBuilder implements ReportBuilderInterface
{

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Doctrine ORM Query builder
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function __construct(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $this->parameters   = $this->configureParameters();
    }

    /**
     * Gets the query instance with default parameters
     *
     * @return \Doctrine\ORM\Query
     * @throws InvalidReportQueryException
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getQuery()
    {
        $queryBuilder = $this->configureBuilder($this->queryBuilder);

        if ($queryBuilder->getType() === \Doctrine\DBAL\Query\QueryBuilder::SELECT) {
            $query = $queryBuilder->getQuery();
            $query = $this->setParameters($query, $this->parameters);
        }
        else {
            throw new InvalidReportQueryException('Only SELECT statements are valid');
        }
        $this->variables = $this->configureVariables();
        return $query;
    }

    /**
     * Gets query parameters
     *
     * @return array
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Configures the query builder
     *
     * <code>
     *      $queryBuilder
     *          ->select('f')
     *          ->from('Foo', 'f')
     *          ->where($queryBuilder->expr()->gt('f.bar', ':min_bar'));
     * </code>
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Doctrine ORM query builder
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    abstract protected function configureBuilder(\Doctrine\ORM\QueryBuilder $queryBuilder);

    /**
     * Configures query parameters
     *
     * <code>
     *      $parameters = array(
     *          'min_bar' => array(
     *              'value' => 5, // default value of the min_bar
     *              'type' => 'number' // form type
     *              'options' => array(
     *                  // array to be passed to the form type
     *                  'label' => 'Min. bar',
     *              ),
     *              'validation' => new Date(),
     *          ),
     *      );
     *
     *      return $parameters;
     * </code>
     *
     * @return array
     */
    abstract protected function configureParameters();

    /**
     * Sets parameters for the query
     *
     * @param \Doctrine\ORM\Query $query     Query to be set parameters on
     * @param array               $variables Variables
     *
     * @return \Doctrine\ORM\Query
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    private function setParameters(\Doctrine\ORM\Query $query, array $variables)
    {
        foreach ($variables as $name => $var) {
            $query->setParameter($name, $var['value']);
        }

        return $query;
    }

}
