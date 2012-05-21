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

namespace Velvel\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Report controller
 *
 * @author r1pp3rj4ck <attila.bukor@gmail.com>
 */
class ReportController extends Controller
{
    /**
     * Lists report types
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function listAction()
    {
        $generator = $this->container->get('velvel.report.generator');

        $reports = $generator->getReportTypes();

        return $this->render('VelvelReportBundle::list.html.twig', array('reports' => $reports));
    }

    /**
     * Shows a report
     *
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP request
     * @param string                                    $reportId Report ID
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author r1pp3rj4ck <attila.bukor@gmail.com>
     */
    public function showAction(Request $request, $reportId)
    {
        $generator = $this->container->get('velvel.report.generator');

        $query = $generator->getQuery($reportId);

        $form = $generator->getForm($reportId);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
//            if ($form->isValid()) {
            $query->setParameters($form->getData());
//            }

            $query->setParameters($form->getData());
        }

        $modifiers = $generator->getModifiers($reportId);

        $result = $query->getResult();

        foreach ($result as &$outer) {
            foreach ($outer as $key => &$value) {
                if (array_key_exists($key, $modifiers)) {
                    $value = call_user_func_array(array($value, $modifiers[$key]['method']), $modifiers[$key]['params']);
                }
            }
        }

        return $this->render('VelvelReportBundle::show.html.twig', array('form'   => $form->createView(),
                                                                         'result' => $result));
    }
}