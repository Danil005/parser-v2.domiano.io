<?php

/**
 * @file Source.php
 * @author Danil Sidorenko
 * @description Создать новый Source
 */

namespace Domian\Console\Make;

use Domian\Console\Console;

class Source extends Console
{
    private function verification()
    {

        if (!isset($this->argv[2])) {
            return $this->response("Please, type name parser. \nExample: php domian make:source CianSource");
        }

        if (strpos($this->argv[2], 'Source') === false) {
            return $this->response("You must add at the end. \nExample: php domian make:source CianSource");
        }

        return 'ok';
    }

    private function sourceId()
    {
        return strtolower(str_replace('Source', '', $this->argv[2]));
    }

    public function make($argv)
    {
        $this->argv = $argv;

        if ($this->verification() != 'ok')
            return $this->verification();


        echo $this->response('Do you want using default function? [yes/no]: ');
        $stdin = fopen('php://stdin', 'r');
        $agree = stream_get_contents($stdin, 1);

        echo $this->response('Do you want change default function list? [yes/no]: ');
        $stdin = fopen('php://stdin', 'r');
        $agree2 = stream_get_contents($stdin, 1);

        $text = templates()->source($argv, $this->sourceId(), $agree, $agree2);


        $fp = fopen(__DIR__."/../../app/Source/".$argv[2] . '.php', "w");

        fwrite($fp, $text);

        fclose($fp);

        return $this->response('Successfully created new Source');
    }
}