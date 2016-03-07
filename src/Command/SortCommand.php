<?php
namespace Kr\MvcCli\Command;


use Kr\MvcCli\MiniCli\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;
class SortCommand extends Command
{
    public function configure()
    {
        $this->setName("sort");
        $this->setDescription("Sort the table");
        $this->addArgument("col", InputArgument::REQUIRED);
        $this->addArgument("direction", InputArgument::REQUIRED);
    }

    /**
     * @inheritdoc
     */
    public function run(InputInterface $input, MvcCliStyle $io, TagInterface $tag)
    {
        $tag->options['sort']['by']          = $input->getArgument("col");
        $tag->options['sort']['direction']   = $input->getArgument("direction");

        return true;
    }
}