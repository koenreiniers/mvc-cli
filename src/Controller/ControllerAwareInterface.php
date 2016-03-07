<?php
namespace Kr\MvcCli\Controller;

interface ControllerAwareInterface
{
    /**
     * Set the controller
     *
     * @param ControllerInterface $controller
     *
     * @return $this
     */
    public function setController(ControllerInterface $controller);
}