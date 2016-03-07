<?php
namespace Kr\MvcCli\MiniCli;

use Kr\MvcCli\Directive\TagInterface;
use Symfony\Component\Console\Input\InputInterface;
use Kr\MvcCli\Style\MvcCliStyle;

class MiniCli
{
    private $commands;

    public function addCommand($command)
    {
        $this->commands[$command->getName()] = $command;
    }

    public function getCommand($command)
    {
        if(isset($this->commands[$command])) {
            return $this->commands[$command];
        }
    }

    public function getCommands()
    {
        return $this->commands;
    }

    public function run(InputInterface $input, MvcCliStyle $io, TagInterface $tag)
    {
        $cmdName = $input->getFirstArgument();
        if($cmdName == "help")
        {
            $headers = ["Command", "Description"];
            $rows = [];
            foreach($this->getCommands() as $command) {
                $rows[] = [$command->getName() . " " . $command->getInputDefinition()->getSynopsis(), $command->getDescription()];
            }
            $io->table($headers, $rows);
        }
        else
        {
            $command = $this->getCommand($cmdName);
            if(!$command) {
                $io->error("Command does not exist");
            }
            else
            {
                try {
                    $input->bind($command->getInputDefinition());
                    return $command->run($input, $io, $tag);
                } catch(\Exception $e) {
                    $io->error($e->getMessage());
                }
            }

        }
    }
}