<?php
namespace Kr\MvcCli\Routing;

class Route
{
    private $name;
    private $controller;
    private $parameters;

    /**
     * Route constructor.
     * @param string $name          - Unique name for this route
     * @param string $controller    - Reference to the controller
     * @param array $parameters     - Available parameters for this route
     */
    public function __construct($name, $controller, $parameters = [])
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}