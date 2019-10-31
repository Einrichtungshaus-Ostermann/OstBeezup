<?php declare(strict_types=1);

/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Beezup
 *
 * @package   OstBeezup
 *
 * @author    Tim Windelschmidt <tim.windelschmidt@ostermann.de>
 * @copyright 2018 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstBeezup\Listeners\Controllers\Frontend;

use Enlight_Event_EventArgs as EventArgs;
use Shopware_Controllers_Frontend_Checkout as Controller;

class Checkout
{
    /**
     * ...
     *
     * @var array
     */
    private $configuration;

    /**
     * ...
     *
     * @var string
     */
    private $viewDir;

    /**
     * ...
     *
     * @param array $configuration
     * @param string $viewDir
     */
    public function __construct(array $configuration, $viewDir)
    {
        // set params
        $this->configuration = $configuration;
        $this->viewDir = $viewDir;
    }

    /**
     * ...
     *
     * @param EventArgs $arguments
     */
    public function onPreDispatch(EventArgs $arguments)
    {
        // get the controller
        /* @var $controller Controller */
        $controller = $arguments->get('subject');

        // get parameters
        $request = $controller->Request();
        $view = $controller->View();

        // only order action
        if (strtolower($request->getActionName()) !== 'finish') {
            // nothing to do
            return;
        }

        // add template dir
        $view->addTemplateDir($this->viewDir);
    }

    /**
     * ...
     *
     * @param EventArgs $arguments
     */
    public function onPostDispatch(EventArgs $arguments)
    {
        // get the controller
        /* @var $controller Controller */
        $controller = $arguments->get('subject');

        // get parameters
        $request = $controller->Request();
        $view = $controller->View();

        // only order action
        if (strtolower($request->getActionName()) !== 'finish') {
            // nothing to do
            return;
        }

        // get the basket
        $basketContent = $view->getAssign('sBasket')['content'];

        // data so set to view
        $data = [
            'ListProductId'        => [],
            'ListProductQuantity'  => [],
            'ListProductUnitPrice' => [],
            'ListProductMargin'    => [],
        ];

        // loop every article
        array_map(function (array $item) use (&$data) {
            $data['ListProductId'][] = $item['ordernumber'];
            $data['ListProductQuantity'][] = $item['quantity'];
            $data['ListProductUnitPrice'][] = str_replace(',', '.', $item['amount']);
        }, $basketContent);

        // loop the data
        foreach ($data as $key => &$value) {
            // and implode the data
            $value = implode('|', $value);
        }

        // set the store id
        $data['storeId'] = $this->configuration['storeId'];

        // and save to view
        $view->assign('ostBeezup', $data);
    }
}
