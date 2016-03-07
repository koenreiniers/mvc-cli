#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';

use Kr\MvcCli\App;
use Kr\MvcCli\Templating\DomTwig\Engine as DomTwigEngine;
use Kr\MvcCli\Controller\Controller as BaseController;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Kr\MvcCli\Controller\ControllerResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ExampleController extends BaseController
{
    public function startAction()
    {
        $this->render(__DIR__ . "/views/start.html.twig");
    }
    public function exampleAction($arguments = ["form"])
    {
        // TODO: Unpack arguments in main controller class
        $example = $arguments[0];
        $this->render(__DIR__ . "/views/examples/".$example.".html.twig");
    }
    public function menuAction()
    {
        $this->render(__DIR__ . "/views/menu.html.twig");
    }
    public function formAction()
    {
        // Note:
        // You can use the 'mvc_cli.controller' event to inject services into the controller
        // You can inject a service container for example

        $io = $this->getApp()->io();
        $this->getScope()->watch("foo", function($oldValue, $newValue) use ($io) {
            $io->note("Controller received foo={$newValue}");
        });
        $this->getScope()->watch("bar", function($oldValue, $newValue) use ($io) {
            $io->note("Controller received bar={$newValue}");
        });
        $this->render(__DIR__ . "/views/form.html.twig");
    }
    public function exitAction()
    {
        $this->render(__DIR__ . "/views/exit.html.twig");
    }
}

// Event dispatcher
$eventDispatcher = new EventDispatcher();

// Default controller resolver (namespace based)
$controllerResolver = new ControllerResolver();

// I/O
$input = new ArgvInput();
$output = new ConsoleOutput();
$debug = true;

$app = new App($eventDispatcher, $controllerResolver, $debug);

// Add dom/twig template engine
$app->addTemplateEngine(new DomTwigEngine());

$app->addRoute("start", [
    "controller" => ["ExampleController", "startAction"]
]);
$app->addRoute("form", [
    "controller" => ["ExampleController", "formAction"]
]);
$app->addRoute("example", [
    "controller" => ["ExampleController", "exampleAction"],
    "parameters" => ["example"]
]);
$app->addRoute("menu", [
    "controller" => ["ExampleController", "menuAction"]
]);
$app->addRoute("exit", [
    "controller" => ["ExampleController", "exitAction"]
]);

$app->handle($input, $output);