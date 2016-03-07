<?php
namespace Kr\MvcCli\Controller;

trait ControllerAwareTrait
{
    /** @var ControllerInterface */
    protected $controller;

    /**
     * @param ControllerInterface $controller
     *
     * @return $this
     */
    public function setController(ControllerInterface $controller)
    {
        $this->controller = $controller;
        return $this;
    }
}