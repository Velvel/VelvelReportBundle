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

/**
 * Form builder
 *
 * @author r1pp3rj4ck <attila.bukor@gmail.com>
 */
abstract class FormBuilder implements FormBuilderInterface
{
    /**
     * @var \Symfony\Component\Form\FormBuilder
     */
    private $formBuilder;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Form\FormBuilder $formBuilder Form builder
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function __construct(\Symfony\Component\Form\FormBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
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
        $form     = $this->formBuilder;
        $formData = array();

        foreach ($parameters as $key => $value) {
            if (isset($value['options'])) {
                $form->add($key, $value['type'], $value['options']);
            }
            else {
                $form->add($key, $value['type']);
            }

            $formData[$key] = $value['value'];
        }

        $form->setData($formData);

        return $form->getForm();
    }
}