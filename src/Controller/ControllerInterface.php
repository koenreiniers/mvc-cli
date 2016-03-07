<?php
namespace Kr\MvcCli\Controller;

use Kr\MvcCli\App;
use Kr\MvcCli\Templating\EngineInterface;

interface ControllerInterface
{
    /**
     * Sets the app
     *
     * @param App $app
     *
     * @return $this
     */
    public function setApp(App $app);

    /**
     * Renders a view
     *
     * @param $view
     */
    public function render($view);

    /**
     * Returns the current scope object for this controller
     *
     * @return Scope
     */
    public function getScope();

    /**
     * Redirects the user to the provided route
     *
     * @param mixed $route
     * @param array $parameters
     *
     * @return mixed
     */
    public function redirectTo($route, $parameters = []);

    /**
     * Return the app
     *
     * @return App
     */
    public function getApp();

}