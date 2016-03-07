<?php
namespace Kr\MvcCli\Controller;

use Kr\MvcCli\Routing\Route;

class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @inheritdoc
     */
    public function getController(Route $route)
    {
        list($class, $method) = $route->getController();
        return [new $class(), $method];
    }
}