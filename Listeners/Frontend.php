<?php

/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Beezup
 *
 * Adds the Beezup Tracking Pixel
 *
 * @package   OstBeezup
 *
 * @author    Tim Windelschmidt <tim.windelschmidt@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstBeezup\Listeners;

use Symfony\Component\VarDumper\VarDumper;

class Frontend
{
    public function onFrontendPostDispatch(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $controller = $args->getSubject();
        $view = $controller->View();

        $view->addTemplateDir($controller->get('service_container')->getParameter('ost_beezup.view_dir'));

        if ($controller->Request()->getActionName() !== 'finish') {
            return;
        }

        $basketContent = $view->getAssign('sBasket')['content'];

        $data = [
            'ListProductId' => [],
            'ListProductQuantity' => [],
            'ListProductUnitPrice' => [],
            'ListProductMargin' => [],
        ];

        array_map(function (array $item) use (&$data) {
            $data['ListProductId'][] = $item['ordernumber'];
            $data['ListProductQuantity'][] = $item['quantity'];
            $data['ListProductUnitPrice'][] = str_replace(',', '.', $item['amountnet']);
        }, $basketContent);

        foreach ($data as $key => &$value) {
            $value = implode('|', $value);
        }
        unset($value);

        $data['storeID'] = $controller->get('ost_beezup.configuration')['storeID'];

        $view->assign('beezup', $data);
    }
}
