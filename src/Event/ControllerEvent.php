<?php
namespace Kr\MvcCli\Event;

use Kr\MvcCli\Controller\ControllerInterface;
use Symfony\Component\EventDispatcher\Event;

class ControllerEvent extends Event
{
    /** @var ControllerInterface */
    private $controller;

    /**
     * ControllerEvent constructor.
     * @param ControllerInterface $controller
     */
    public function __construct(ControllerInterface $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return ControllerInterface
     */
    public function getController()
    {
        return $this->controller;
    }
}