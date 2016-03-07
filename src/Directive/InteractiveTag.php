<?php
namespace Kr\MvcCli\Directive;

use Doctrine\Common\Collections\ArrayCollection;
use Kr\MvcCli\Command\PageCommand;
use Kr\MvcCli\Command\ResetCommand;
use Kr\MvcCli\Command\SearchCommand;
use Kr\MvcCli\Command\SortCommand;
use Kr\MvcCli\MiniCli\MiniCli;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Kr\MvcCli\Style\MvcCliStyle;

abstract class InteractiveTag extends AbstractTag
{
    /**
     * @inheritdoc
     * + Checks if element is interactive
     */
    public function execute(MvcCliStyle $io)
    {
        $this->mergeOptions();

        $result = $this->render($io);

        $options = $this->getOptions();
        if($options['interactive']) {
            $result = $this->interact($io);
        }

        return $result;
    }

    /**
     * Create interactive command prompt
     *
     * @param MvcCliStyle $io
     *
     * @param array $options
     *
     * @return mixed
     */
    public function interact(MvcCliStyle $io)
    {
        $miniCli = new MiniCli();

        // TODO: EventDispatcher oid
        $miniCli->addCommand(new PageCommand());
        $miniCli->addCommand(new SearchCommand());
        $miniCli->addCommand(new SortCommand());
        $miniCli->addCommand(new ResetCommand());

        while(($line = $io->ask("Type 'help' for possible commands. 'exit' to exit this prompt")) != "exit")
        {
            $input = new StringInput($line);
            $reRender = $miniCli->run($input, $io, $this);
            if($reRender) {
                $result = $this->render($io);
            }
        }
        return $result;
    }
}