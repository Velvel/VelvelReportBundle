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

namespace Velvel\ReportBundle\Form;

use Symfony\Component\Validator\Constraints\Collection;

/**
 * Form builder
 *
 * @author r1pp3rj4ck <attila.bukor@gmail.com>
 */
class FormBuilder implements FormBuilderInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory Form factory
     *
     * @author   r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function __construct(\Symfony\Component\Form\FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Gets a form
     *
     * @param array $parameters Parameters
     *
     * @return \Symfony\Component\Form\Form
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function getForm(array $parameters)
    {
        $formData        = array();
        $validationArray = array();
        foreach ($parameters as $key => $value) {
            if (isset($value['value'])) {
                $formData[$key] = $value['value'];
            }
            if (isset($value['validation'])) {
                $validationArray = $value['validation'];
            }
        }
        $validationConstraint = new Collection($validationArray);
        $form = $this->formFactory->createBuilder('form', $formData, array('validation_constraint' => $validationConstraint));

        foreach ($parameters as $key => $value) {
            if (isset($value['options'])) {
                $form->add($key, $value['type'], $value['options']);
            }
            else {
                $form->add($key, $value['type']);
            }
        }

        return $form->getForm();
    }
}