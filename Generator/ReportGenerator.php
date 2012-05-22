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

namespace Velvel\ReportBundle\Generator;

use Doctrine\ORM\EntityManager;
use Velvel\ReportBundle\Form\FormBuilder;

use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * Report generator
 *
 * @author r1pp3rj4ck <attila.bukor@gmail.com>
 */
class ReportGenerator
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Velvel\ReportBundle\Form\FormBuilder
     */
    private $formBuilder;

    /**
     * @var array
     */
    private $reports;

    /**
     * @var string
     */
    private $validInterface;

    /**
     * @var string
     */
    private $showTemplate;

    /**
     * @var string
     */
    private $listTemplate;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager           $entityManager Entity manager
     * @param \Velvel\ReportBundle\Form\FormBuilder $formBuilder   Form builder
     * @param array                                 $reports       Reports
     * @param string                                $showTemplate  Show template
     * @param string                                $listTemplate  List template
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function __construct(EntityManager $entityManager, FormBuilder $formBuilder, array $reports, $showTemplate, $listTemplate)
    {
        $this->entityManager  = $entityManager;
        $this->formBuilder    = $formBuilder;
        $this->validInterface = "Velvel\\ReportBundle\\Builder\\ReportBuilderInterface";
        $this->reports        = $reports;
        $this->showTemplate   = $showTemplate;
        $this->listTemplate   = $listTemplate;
    }

    /**
     * Gets query
     *
     * @param string $reportId Report ID
     *
     * @return mixed
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getQuery($reportId)
    {
        $builder = $this->getBuilder($reportId);
        return $builder->getQuery();
    }

    /**
     * Gets form
     *
     * @param string $reportId Report ID
     *
     * @return \Symfony\Component\Form\Form
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getForm($reportId)
    {
        $builder    = $this->getBuilder($reportId);
        $parameters = $builder->getParameters();

        return $this->formBuilder->getForm($parameters);
    }

    /**
     * Gets modifiers
     *
     * @param string $reportId Report ID
     *
     * @return array
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getModifiers($reportId)
    {
        $builder = $this->getBuilder($reportId);

        return $builder->getModifiers();
    }

    /**
     * Gets report types
     *
     * @return array
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getReportTypes()
    {
        return $this->reports;
    }

    /**
     * Gets show template
     *
     * @return string
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getShowTemplate()
    {
        return $this->showTemplate;
    }

    /**
     * Gets list template
     *
     * @return string
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getListTemplate()
    {
        return $this->listTemplate;
    }

    /**
     * Gets report builder
     *
     * @param string $reportId Report ID
     *
     * @return object
     * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    protected function getBuilder($reportId)
    {
        $className  = $this->reports[$reportId]['class'];
        $reflection = new \ReflectionClass($className);
        if ($reflection->implementsInterface($this->validInterface)) {
            $builder = $reflection->newInstanceArgs(array($this->entityManager->createQueryBuilder()));
        }
        else {
            throw new RuntimeException(sprintf("ReportBuilders have to implement %s, and %s doesn't implement it", $this->validInterface, $className));
        }

        return $builder;
    }
}
