<?php

/**
 * @file Console.php
 * @author Danil Sidorenko
 * @description Консоль для управления проектом
 */

namespace Domian\Console;

use Domian\Console\Make\Source;

class Console
{
    private $command;
    protected $argv;

    public function run($argv)
    {
//        $stdin = fopen('php://stdin', 'r');
//        echo stream_get_contents($stdin);
        $this->command = $argv[1];
        $this->argv = $argv;

        switch ($this->command) {
            case "make:source":
                return (new Source())->make($argv);
                break;
            case "command-list":
                break;
            default:
                return $this->response("Command not found. Command list: php domian command-list");
                break;
        }
    }

    /**
     * Получить команду
     *
     * @return mixed
     */
    protected function getCommand()
    {
        return $this->command;
    }

    protected function response($message)
    {
        return "\n[Domian Console] " . $message . "\n";
    }
}