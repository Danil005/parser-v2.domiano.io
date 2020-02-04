<?php 

/**
 * @file DomofonSource.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface; 
use App\Traits\SourceTrait; 
use Exception; 

class DomofonSource implements SourceInterface
{
    use SourceTrait; 

    /**
     * Устновить source_id
     * 
     * @return string
     */
    protected function sourceId()
    {
        return 'domofon';
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
        $full_square = $this->getValue('full_square');

        $square = str_replace(',', '.', $full_square);
        $square = explode('/', $square);

        if( isset($square[1]) )
            $square = $square[1];
        else {
            $square = $square[0];
        }
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
        $living_square = $this->getValue('living_square');

        return floatval(str_replace(',', '.', $living_square));
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
        $kitchen_square = $this->getValue('kitchen_square');

        return floatval(str_replace(',', '.', $kitchen_square));
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
        
    }

}