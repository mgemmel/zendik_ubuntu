<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Mvc\Controller;

use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Basic action controller
 */
abstract class AbstractActionController extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected $eventIdentifier = __CLASS__;

    /**
     * Default action if none provided
     *
     * @return array
     */
    public function indexAction()
    {
        return new ViewModel([
            'content' => 'Placeholder page'
        ]);
    }

    /**
     * Action called if matched action does not exist
     *
     * @return array
     */
    public function notFoundAction()
    {
        $event      = $this->getEvent();
        $routeMatch = $event->getRouteMatch();
        $routeMatch->setParam('action', 'not-found');

        $helper = $this->plugin('createHttpNotFoundModel');
        return $helper($event->getResponse());
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        $actionResponse = $this->$method();

        $e->setResult($actionResponse);

        return $actionResponse;
    }
}
