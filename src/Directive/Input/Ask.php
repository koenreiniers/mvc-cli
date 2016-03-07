<?php
namespace Kr\MvcCli\Directive\Input;

use Kr\MvcCli\Directive\InputTagInterface;
use Kr\MvcCli\Style\MvcCliStyle;
use Kr\MvcCli\Directive\OldTagInterface;

class Ask implements OldTagInterface
{
    /**
     * @inheritdoc
     */
    public static function render(MvcCliStyle $io, \DOMElement $dom)
    {
        $options = [
            "question"  => null,
            "default"   => null,
            "output"    => null,
            "hidden"    => false,
            "constraints" => [],
        ];

        // DOM
        $options['output'] = $dom->getAttribute("name");
        $options['hidden'] = $dom->getAttribute("hidden");

        $options['question'] = $dom->getElementsByTagName("question")->item(0)->nodeValue;
        $options['default'] = $dom->getAttribute("default");

        $constraints = $dom->getElementsByTagName("constraint");
        foreach($constraints as $constraint)
        {
            $options['constraints'][] = $constraint->getAttribute("expr");
        }




        // TODO: Move to separate service for reusability
        $expressions = $options['constraints'];
        $options['validator'] = function($value) use($expressions, $io)
        {
            foreach($expressions as $expression)
            {
                $phpized = str_replace(":v", "\$value", $expression);
                $isCorrect = eval("return $phpized;");
                if(!$isCorrect) {
                    throw new \Exception("Validation failed: ".$expression);
                }
            }

            return $value;
        };
        if(count($expressions) > 0) {
            array_unshift($expressions, "Constraints:");
            $io->debug($expressions);
        }




        if($options['hidden']) {
            $answer = $io->askHidden($options['question'], $options['validator']);
        } else {
            $answer = $io->ask($options['question'], $options['default'], $options['validator']);
        }

        return [
            $options['output'] => $answer,
        ];
    }
}