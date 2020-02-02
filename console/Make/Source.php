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
        return lcfirst(str_replace('Source', '', $this->argv[2]));
    }

    public function make($argv)
    {
        $this->argv = $argv;

        if ($this->verification() != 'ok')
            return $this->verification();

        $text = templates()->source($argv, $this->sourceId());

        $fp = fopen(__DIR__."/../../app/Source/".$argv[2] . '.php', "w");

        fwrite($fp, $text);

        fclose($fp);

        return $this->response('Successfully created new Source');
    }
}