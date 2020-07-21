<?php 

/**
 * @file M2Source.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface; 
use App\Traits\SourceTrait; 
use Exception; 

class M2Source implements SourceInterface
{
    use SourceTrait; 

    /**
     * Устновить source_id
     * 
     * @return string
     */
    protected function sourceId()
    {
        return 'm2';
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
        $square = $this->getValue('description');

        $pos = strpos($square, 'м²');
        $square = substr($square, 0, $pos);
        $square = explode(',', $square)[1];
        $square = str_replace('.', ',', $square);

        return floatval($square);
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
        return false;
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
        return false;
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
        $rooms = $this->getValue('description');
        $pos = strpos($rooms, 'к');
        $rooms = substr($rooms, 0, $pos);
        $this->return_data[] = $this->dictionary->rooms(intval($rooms));

        $floor = $this->getValue('description');
        $pos = strpos($floor, 'эт');
        $floor = substr($floor, 0, $pos);
        $floor = explode(',', $floor)[2];
        $house_storey = explode('/', $floor);
        $floor = intval($house_storey[0]);
        $house_storey = intval($house_storey[1]);

        $this->return_data[] = ['floor' => $floor];
        $this->return_data[] = ['house_storey' => $house_storey];
    }

}