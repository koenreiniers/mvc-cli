<?php
namespace Kr\MvcCli\Directive\Output;

use Kr\MvcCli\Directive\AbstractTag;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\TagInterface;


class Message extends AbstractTag implements TagInterface
{
    const TAGS = "message|note|caution|success|error|warning|comment|text";

    /**
     * @inheritdoc
     */
    public function configure()
    {
        $options = [
            "type"  => "note",
        ];
        $this->setDefaultOptions($options);
    }

    /**
     * @inheritdoc
     */
    public function loadDom(\DOMElement $dom, array $options = [])
    {
        // Get message type
        $tag = $dom->nodeName;
        $options['type'] = $tag;

        if($dom->getAttribute("type")) {
            $options['type'] = $dom->getAttribute("type");
        }
        // Get messages
        $options['messages'] = [];

        $content = $dom->nodeValue;
        $lines = explode(PHP_EOL, $content);
        $lines = array_map("trim", $lines);
        foreach($lines as $line)
        {
            if($line != "") {
                $options['messages'][] = $line;
            }
        }

        return $options;
    }

    /**
     * @inheritdoc
     */
    public function render(MvcCliStyle $io)
    {
        $options = $this->getOptions();
        $type = "note";
        $validTypes = ["note", "caution", "warning", "error", "success", "text", "comment"];
        if(in_array($options['type'], $validTypes)) {
            $type = $options['type'];
        }

        $messages = $options['messages'];

        call_user_func([$io, $type], $messages);
    }
}