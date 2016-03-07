<?php
namespace Kr\MvcCli\Routing;

class RouteCollection
{
    private $routes;
    private $default;

    /**
     * RouteCollection constructor.
     * @param array $routes         - Array of routes
     */
    public function __construct($routes = [])
    {
        $this->routes = $routes;
        $this->default = null;
    }

    /**
     * Add a new route to the collection
     *
     * @param Route $route
     *
     * @return $this
     */
    public function add(Route $route)
    {
        $this->routes[$route->getName()] = $route;
        return $this;
    }

    /**
     * Check if collection contains the route
     *
     * @param string $name
     *
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->routes[$name]);
    }

    /**
     * Find route by name
     *
     * @param string $name
     *
     * @return Route|null
     */
    public function get($name)
    {
        if(!$this->has($name)) {
            return null;
        }
        return $this->routes[$name];
    }

    /**
     * Delete route by name
     *
     * @param string $name
     *
     * @return $this
     */
    public function delete($name)
    {
        unset($this->routes[$name]);
        return $this;
    }

    /**
     * Return the default route
     * If it's not set, return the first route
     *
     * @return Route|null
     */
    public function getDefault()
    {
        if($this->default === null) {
            return $this->first();
        }
        return $this->default;
    }

    /**
     * Sets the default route
     *
     * @param string $name
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function setDefault($name)
    {
        if(!$this->has($name)) {
            throw new \Exception("Route {$name} does not exist");
        }
        $this->default = $this->get($name);
        return $this;
    }

    /**
     * Return the first route in the collection
     *
     * @return Route|null
     */
    public function first()
    {
        return reset($this->routes);
    }
}