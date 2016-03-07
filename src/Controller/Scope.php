<?php
namespace Kr\MvcCli\Controller;

class Scope
{
    private $controller;
    private $data = [];
    private $watchers;

    public function __construct(ControllerInterface $controller, $parameters = [])
    {
        $this->controller = $controller;
        foreach($parameters as $key => $value) {
            $this->set($key, $value);
        }
        $this->watchers = [];
    }

    /**
     * Returns the controller instance to which this scope belongs
     *
     * @return ControllerInterface
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Apply new values to scope
     *
     * @param array $values
     *
     * @return $this
     */
    public function apply(array $values = [])
    {
        foreach($values as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    /**
     * Watch a variable for changes
     *
     * @param $key                  - Key of the variable to watch
     * @param callable $callback    - Executed when the variable has been changed
     */
    public function watch($key, $callback)
    {
        $this->watchers[] = ["key" => $key, "callback" => $callback];
    }

    /**
     * Set a new variable
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $oldValue = $this->get($key);

        // Execute watcher callbacks
        $watchers = $this->getWatchers($key);
        foreach($watchers as $watcher)
        {
            $watcher['callback']($oldValue, $value);
        }
        $this->getController()->getApp()->io()->debug("Updated {$key}: {$oldValue} => {$value}");
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Return all watchers for a given key
     *
     * @param string $key
     *
     * @return array
     */
    private function getWatchers($key)
    {
        $watchers = [];
        foreach($this->watchers as $watcher)
        {
            if($watcher['key'] !== $key) {
                continue;
            }
            $watchers[] = $watcher;
        }
        return $watchers;
    }

    /**
     * Check if scope contains key
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Return value of key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if(!$this->has($key)) {
            return null;
        }
        return $this->data[$key];
    }

    /**
     * Return scope as associative array
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}