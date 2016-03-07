<?php
namespace Kr\MvcCli\Style;

use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Available colors: black, red, green, yellow, blue, magenta, cyan, white, default
 * Class MvcCliStyle
 * @package Kr\MvcCli\Style
 */
class MvcCliStyle extends SymfonyStyle
{
    private $isDebugMode;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param boolean $isDebugMode
     */
    public function __construct(InputInterface $input, OutputInterface $output, $isDebugMode = false)
    {
        $this->isDebugMode = $isDebugMode;
        parent::__construct($input, $output);
    }

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->isDebugMode;
    }

    /**
     * Writes debug message if debug mode is on
     * TODO: Log message when debug mode is off
     */
    public function debug($message, $break = false)
    {
        if(!$this->isDebugMode()) {
            return;
        }
        $this->block($message, 'DEBUG', 'fg=blue', ' ! ');

        if($break) {
            $this->text("<Press any key to continue>");
            readline();
        }

    }
}