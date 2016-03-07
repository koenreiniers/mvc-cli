<?php
namespace Kr\MvcCli\Routing;

class Router
{
    /** @var RouteCollection */
    private $routes;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * Returns the default route
     *
     * @return mixed|null
     */
    public function getDefaultRoute()
    {
        return $this->routes->getDefault();
    }

    /**
     * Add a new route
     *
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    public function addRoute($name, array $options)
    {
        $controller = $options['controller'];
        $parameters = isset($options['parameters']) ? $options['parameters'] : [];
        $route = new Route($name, $controller, $parameters);
        $this->routes->add($route);
        return $this;
    }

    /**
     * Return whether or not a route exists
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasRoute($name)
    {
        return $this->routes->has($name);
    }

    /**
     * Find route by name
     *
     * @param $name
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getRoute($name)
    {
        $route = $this->routes->get($name);
        if(!$route) {
            throw new \Exception("Route '{$name}' does not exist");
        }
        return $route;
    }

    /**
     * Set the default route
     *
     * @param string $name
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function setDefaultRoute($name)
    {
        $this->routes->setDefault($name);
        return $this;
    }

}