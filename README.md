VelvelReportBundle
==================

The VelvelReportBundle adds support to create custom report pages for administrators about the database, also
lets the administrators write their own DQL and SQL queries to fetch data they need.

## Installation

Add `"velvel/report-bundle": "dev-master"` to your composer.json

### Register the bundle

``` php
<?php

// app/AppKernel.php

public function registerBundles {
    return array(
        // ...
        new Velvel\ReportBundle\VelvelReportBundle(),
        // ...
    );
}
```

## Usage

Create a report file which extends the `Velvel\ReportBuilder\Builder\BaseReportBuilder`:

``` php
<?php

namespace Acme\DemoBundle\Report;

use Velvel\ReportBundle\Builder\BaseReportBuilder;
use Doctrine\ORM\QueryBuilder;

class OrdersReport extends BaseReportBuilder
{
    /**
     * Configures builder
     * 
     * This method configures the ReportBuilder. It has to return
     * a configured Doctrine QueryBuilder.
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function configureBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->select('p.name, p.price, c.checkoutDate')
            ->from('Cart', 'c')
            ->join('c.items', 'i')
            ->join('i.product', 'p')
            ->add('where', 'c.checkoutDate > :from AND c.checkoutDate < :to');
        return $queryBuilder;
    }

    /**
     * Configures parameters
     *
     * This method configures parameters, which will be passed to
     * the QueryBuilder and the Form too, so the users (admins) can
     * change them.
     *
     * @return array
     */
    public function configureParameters()
    {
        $parameters = array(
            'from' => array(
                'value'   => new \DateTime('yesterday'), // default value
                'type'    => 'date', // form type
                'options' => array('label' => 'From'), // form options
            ),
            'to'   => array(
                'value'   => new \DateTime('now'),
                'type'    => 'date',
                'options' => array('label' => 'To'),
            ),
        );
        return $parameters;
    }

    /**
     * Configures modifiers
     *
     * If an element in the select statement returns an object without
     * a __toString() method implemented, it needs a modifier to be set.
     *
     * @return array
     */
    public function configureModifiers()
    {
        $modifiers = array(
            'checkoutDate' => array(
                'method' => 'format', // method to be called on the object
                'params' => array('Y/m/d H:i:s'), // method parameters in an array
            ),
        );

        return $modifiers;
    }
}
```

Add your new `ReportBuilder` to your `config.yml`:

``` yaml
velvel_report:
    reports:
        - { id: 'orders', name: 'Orders', class: 'Acme\DemoBundle\Report\OrdersReport' }
```
> The **id** must be unique, it will be used in the routing. The **name** will be rendered in the reports list and the **class** is your report class.

## Configuration
You can override the show and list templates.

``` yaml
velvel_report:
    templates:
        show: 'VelvelReportBundle::show.html.twig' # this is the default show template
        list: 'VelvelReportBundle::list.html.twig' # this is the default list template
```