<?php
namespace Kr\MvcCli\Controller;

use Kr\MvcCli\App;
use Kr\MvcCli\Templating\EngineInterface;

abstract class Controller implements ControllerInterface
{
    /** @var App  */
    protected $app;

    /** @var Scope */
    protected $scope;

    public function __construct()
    {
        $this->scope = new Scope($this);
    }

    /**
     * Sets the app
     *
     * @param App $app
     *
     * @return $this
     */
    public function setApp(App $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Renders a view
     *
     * @param $view
     */
    public function render($view)
    {
        // TODO: Move this to another service i.e. a ViewResolver
        $filePath = $view;

        // Get file extension
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        while($subExt = pathinfo(substr($filePath, 0, -(strlen($ext) + 1)), PATHINFO_EXTENSION))
        {
            $ext = $subExt . "." . $ext;
        }

        $templateEngine = $this->getApp()->getTemplateEngine($ext);
        $templateEngine->render($filePath, $this->getScope());
    }

    /**
     * @inheritdoc
     */
    public function redirectTo($route, $parameters = [])
    {
        $this->getApp()->io()->debug("Redirecting to route:{$route} with parameters:[".implode(",", $parameters)."]");
        $this->getApp()->goto($route, $parameters);
    }

    /**
     * @inheritdoc
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @inheritdoc
     */
    public function getApp()
    {
        return $this->app;
    }
}