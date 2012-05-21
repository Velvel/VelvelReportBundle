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
     * @var array
     */
    private $modifiers;

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
        $this->modifiers    = $this->configureModifiers();
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
     * Gets modifiers
     *
     * @return array
     *
     * @author r1pp3rj4ck
     */
    public function getModifiers()
    {
        return $this->modifiers;
    }

    /**
     * Configures the query builder
     *
     * <code>
     *      $queryBuilder
     *          ->select('f.length')
     *          ->from('Foo', 'f')
     *          ->where($queryBuilder->expr()->gt('f.bar', ':min_bar'));
     *
     *      return $queryBuilder;
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
     * Configures modifiers
     *
     * <code>
     *      $modifiers = array(
     *          'length' => array(
     *              'method' => 'format',
     *              'params' => array(
     *                  'H:i;s'
     *              ),
     *          ),
     *      );
     *
     *      return $modifiers;
     *
     * </code>
     *
     * @return array
     */
    abstract protected function configureModifiers();

    /**
     * Sets parameters for the query
     *
     * @param \Doctrine\ORM\Query $query      Query to be set parameters on
     * @param array               $parameters Variables
     *
     * @return \Doctrine\ORM\Query
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    private function setParameters(\Doctrine\ORM\Query $query, array $parameters)
    {
        foreach ($parameters as $name => $param) {
            $query->setParameter($name, $param['value']);
        }

        return $query;
    }

}
