<?php

/**
 * @file ParseMethod.php
 * @author Danil Sidorenko
 * @description Метод для парсинга
 * @method /parse
 */

namespace App\Methods;

use App\Api;
use App\Exceptions\MessageNotExistException;
use App\Exceptions\WrongSourceException;
use App\Interfaces\MethodsInterface;
use App\Traits\MethodsTrait;

class ParseMethod extends Api implements MethodsInterface
{
    use MethodsTrait;

    /**
     * Метод для вызова метода API
     *
     * @return string
     * @throws MessageNotExistException
     */
    public function call()
    {
        $data = $this->getData();

        if (!isset($data[0])) {
            $source = ucfirst(mb_strtolower($this->getSource()));
            $class = "App\\Source\\" . $source . 'Source';

            if (!class_exists($class))
                return response()->error()
                    ->setMessage('Source "' . $this->getSource() . '" not exist. Please, check source in data.')
                    ->setData(array_keys(fields()))
                    ->send();

            $object = (new $class($this->getData()));
            $source_id = $object->source_id;

            if ($source_id != $this->getSource())
                return response()->error()
                    ->setMessage(
                        'Invalid "source_id": '.((!$source_id) ? 'empty' : $source_id).' in ' . $class . '. '.
                        'Maybe you mean: ' . lcfirst($source) . "? " .
                        'Create function "sourceId()" and return string with "source_id".'
                        )
                    ->send();

            $object->call();
        }
    }
}