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
     * @return array|string
     * @throws MessageNotExistException
     */
    public function call()
    {
        if( !isset($this->request_body[0]) ) {
            $data = $this->getData();


            if (!isset($data[0])) {
                $source = ucfirst(mb_strtolower($this->getSource()));
                if ($source == 'Without-realtor')
                    $class = "App\\Source\\WithoutRealtorSource";
                elseif ($source == 'Domclick')
                    $class = "App\\Source\\DomClickSource";
                elseif ($source == 'Donrio')
                    $class = "App\\Source\\DonRioSource";
                elseif ($source == 'Rsonline')
                    $class = "App\\Source\\RSOnlineSource";
                else
                    $class = "App\\Source\\" . $source . 'Source';
                if (!class_exists($class)) {
                    return response()->error()
                        ->setMessage('Source "' . $this->getSource() . '" not exist. Please, check source in data.')
                        ->setData(array_keys(fields()))
                        ->send();
                }

                $object = (new $class($this->getData()));
                $source_id = $object->source_id;

                if ($source_id != $this->getSource()) {
                    $object->not_response = true;
                    return response()->error()
                        ->setMessage(
                            'Invalid "source_id": ' . ((!$source_id) ? 'empty' : $source_id) . ' in ' . $class . '. ' .
                            'Maybe you mean: ' . lcfirst($source) . "? " .
                            'Create function "sourceId()" and return string with "source_id".'
                        )
                        ->send();
                }

                $object->call();
            }
        } else {
            foreach($this->request_body as $item) {
                $data = $this->getData($item);

                if (!isset($data[0])) {
                    $source = ucfirst(mb_strtolower($this->getSource($item)));
                    if ($source == 'Without-realtor')
                        $class = "App\\Source\\WithoutRealtorSource";
                    elseif ($source == 'Domclick')
                        $class = "App\\Source\\DomClickSource";
                    elseif ($source == 'Donrio')
                        $class = "App\\Source\\DonRioSource";
                    elseif ($source == 'Rsonline')
                        $class = "App\\Source\\RSOnlineSource";
                    else
                        $class = "App\\Source\\" . $source . 'Source';
                    if (!class_exists($class)) {
                        return response()->error()
                            ->setMessage('Source "' . $this->getSource($item) . '" not exist. Please, check source in data.')
                            ->setData(array_keys(fields()))
                            ->send();
                    }

                    $object = (new $class($this->getData($item)));
                    $source_id = $object->source_id;

                    if ($source_id != $this->getSource($item)) {
                        $object->not_response = true;
                        return response()->error()
                            ->setMessage(
                                'Invalid "source_id": ' . ((!$source_id) ? 'empty' : $source_id) . ' in ' . $class . '. ' .
                                'Maybe you mean: ' . lcfirst($source) . "? " .
                                'Create function "sourceId()" and return string with "source_id".'
                            )
                            ->send();
                    }
                    $object->call();
                    $result[] = $object->getResult();

                }
            }
            echo response()->success()->setMessage('Successfully formatted data')
                ->setData($result)->send();
            die();
        }
    }
}