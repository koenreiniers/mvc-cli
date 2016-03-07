<?php
namespace Kr\MvcCli\Controller;

use Kr\MvcCli\Routing\Route;

interface ControllerResolverInterface
{
    /**
     * Resolve controller from route
     *
     * @param Route $route
     *
     * @return ControllerInterface
     */
    public function getController(Route $route);
}