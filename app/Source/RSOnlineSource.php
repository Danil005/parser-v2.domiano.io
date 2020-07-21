<?php

/**
 * @file RSOnlineSource.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface;
use App\Traits\SourceTrait;
use Exception;

class RSOnlineSource implements SourceInterface
{
    use SourceTrait;

    /**
     * Устновить source_id
     *
     * @return string
     */
    protected function sourceId()
    {
        return 'rsonline';
    }

    /**
     * Спарсить полную площадь
     * Если оставить false, то парсить не будет.
     * Если убрать return false, то нужно будет реализовать метод и
     * вернуть значение.
     *
     * @return mixed
     */
    protected function fullSquare()
    {
        $floor_square = $this->getValue('floor_square');

        $re = '/[\p{Cyrillic}]+\s([0-9,]+)/miu';
        preg_match_all($re, $floor_square, $matches, PREG_SET_ORDER, 0);


        return isset($matches[0][1]) ? floatval(str_replace(',', '.', $matches[0][1])) : "";
    }

    /**
     * Спарсить жилую площадь
     * Если оставить false, то парсить не будет.
     * Если убрать return false, то нужно будет реализовать метод и
     * вернуть значение.
     *
     * @return mixed
     */
    protected function livingSquare()
    {
        $floor_square = $this->getValue('floor_square');

        $re = '/[\p{Cyrillic}]+\s[0-9,]+\/([0-9,]+)/miu';
        preg_match_all($re, $floor_square, $matches, PREG_SET_ORDER, 0);

        return isset($matches[0][1]) ? floatval(str_replace(',', '.', $matches[0][1])) : "";
    }

    /**
     * Спарсить площадь кухни
     * Если оставить false, то парсить не будет.
     * Если убрать return false, то нужно будет реализовать метод и
     * вернуть значение.
     *
     * @return mixed
     */
    protected function kitchenSquare()
    {
        $floor_square = $this->getValue('floor_square');

        $re = '/[\p{Cyrillic}]+\s[0-9,]+\/[0-9,]+\/([0-9,]+)/miu';
        preg_match_all($re, $floor_square, $matches, PREG_SET_ORDER, 0);

        return isset($matches[0][1]) ? floatval(str_replace(',', '.', $matches[0][1])) : "";
    }

    /**
     * Основная функция, которая выполняется, когда запускается
     * парсер. Здесь находится логика парсера.
     *
     * @return mixed
     * @throws Exception
     */
    public function call()
    {

        $temp = [];

        foreach ($this->return_data as $value) {
            if (is_array($value))
                foreach ($value as $field => $data) {
                    $temp[$field] = $data;
                }
        }

        $temp['price'] = intval(preg_replace('/\D/', '', $this->getValue('price'))) * 1000;

        $this->return_data[] = $temp;


        $rooms = $this->getValue('rooms');

        $this->return_data[] = $this->dictionary->rooms(intval($rooms));

        if ($temp['section_name'] == 'apartment') {
            $this->return_data[] = ['house_type' => $this->getHouseType()];
            if (!(strpos($this->getValue('floor_square'), '?') !== false)) {
                $this->return_data[] = ['floor' => $this->extractFloor()];
                $this->return_data[] = ['house_storey' => $this->extractHouseStorey()];
            }
        } elseif ($temp['section_name'] == 'house') {
            $this->return_data[] = ['wall_material' => $this->getWallMaterial()];
            if (!(strpos($this->getValue('floor_square'), '?') !== false))
                $this->return_data[] = ['house_storey' => $this->extractHouseStorey()];
        }
    }

    private function extractFloor()
    {
        $floor_square = $this->getValue('floor_square');

        $re = '/(\d+)\/\d+/miu';
        preg_match_all($re, $floor_square, $matches, PREG_SET_ORDER, 0);

        return isset($matches[0][1]) ? intval($matches[0][1]) : "";
    }

    private function extractHouseStorey()
    {
        $floor_square = $this->getValue('floor_square');

        $re = '/\d+\/(\d+)/miu';
        preg_match_all($re, $floor_square, $matches, PREG_SET_ORDER, 0);

        return isset($matches[0][1]) ? intval($matches[0][1]) : "";
    }

    private function extractWallMaterial()
    {
        $floor_square = $this->getValue('floor_square');

        $re = '/([\p{Cyrillic}]+)/miu';
        preg_match_all($re, $floor_square, $matches, PREG_SET_ORDER, 0);

        return isset($matches[0][1]) ? $matches[0][1] : "";
    }

    private function getHouseType()
    {
        $wallMaterial = $this->extractWallMaterial();

        switch ($wallMaterial) {
            case 'кир':
                return 'brick';
                break;
            case 'пан':
                return 'panel';
                break;
            default:
                return 'other';
                break;
        }
    }

    private function getWallMaterial()
    {
        $wallMaterial = $this->extractWallMaterial();

        switch ($wallMaterial) {
            case 'кир':
                return 'brick';
                break;
            default:
                return 'other';
                break;
        }
    }
}