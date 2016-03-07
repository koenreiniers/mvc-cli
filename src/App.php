<?php
namespace Kr\MvcCli;

use Kr\MvcCli\Controller\ControllerResolverInterface;
use Kr\MvcCli\Event\ControllerEvent;
use Kr\MvcCli\Event\ResponseEvent;
use Kr\MvcCli\Routing\Router;
use Kr\MvcCli\Directive\OutputTagInterface;
use Kr\MvcCli\Directive\TagRegistry;
use Kr\MvcCli\Templating\EngineInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kr\MvcCli\Style\MvcCliStyle;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class App
{
    private $controllerResolver;
    private $eventDispatcher;
    private $tagRegistry;
    private $io;
    private $isDebug;
    private $templateEngines;

    public function __construct(EventDispatcherInterface $eventDispatcher, ControllerResolverInterface $controllerResolver, $isDebug = false)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->controllerResolver = $controllerResolver;
        $this->isDebug = $isDebug;

        $this->templateEngines = [];
        $this->tagRegistry = new TagRegistry();
        $this->router = new Router();
    }

    /**
     * @return TagRegistry
     */
    public function getTagRegistry()
    {
        return $this->tagRegistry;
    }

    /**
     * @return bool
     */
    private function isDebug()
    {
        return $this->isDebug;
    }

    /**
     * Adds a new template engine
     *
     * @param EngineInterface $templateEngine
     *
     * @return $this
     */
    public function addTemplateEngine(EngineInterface $templateEngine)
    {
        $templateEngine->setTagRegistry($this->getTagRegistry());
        $this->templateEngines[$templateEngine->getFileExtension()] = $templateEngine;
        return $this;
    }

    /**
     * Find template engine by file extension
     * @param string $fileExtension
     * @return EngineInterface
     */
    public function getTemplateEngine($fileExtension)
    {
        return $this->templateEngines[$fileExtension];
    }

    /**
     * handle I/O
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $this->io = new MvcCliStyle($input, $output, $this->isDebug());

        $defaultRoute = $this->router->getDefaultRoute();
        $this->goto($defaultRoute->getName());
    }

    /**
     * Return I/O object
     *
     * @return MvcCliStyle
     */
    public function io()
    {
        return $this->io;
    }

    /**
     * Sets the default route
     *
     * @param string $name
     *
     * @return $this
     */
    public function setDefaultRoute($name)
    {
        $this->router->setDefaultRoute($name);
        return $this;
    }

    /**
     * Add new route
     *
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addRoute($name, array $options = [])
    {
        return $this->router->addRoute($name, $options);
    }

    /**
     * @param string $routeName
     * @param array $arguments
     */
    public function goto($routeName, array $arguments = [])
    {
        $route = $this->router->getRoute($routeName);

        // Resolve controller
        list($controller, $action) = $this->controllerResolver->getController($route);

        // Inject app into the controller
        $controller->setApp($this);

        // Dispatch mvc_cli.controller event
        $this->eventDispatcher->dispatch("mvc_cli.controller", new ControllerEvent($controller));

        call_user_func_array([$controller, $action], [$arguments]);
    }

    /**
     * Shuts down the CLI app
     */
    public function terminate()
    {
        $this->io->text("Shutting down..");
        // TODO: Dispatch console.terminate event ..maybe..?
        exit();
    }
}