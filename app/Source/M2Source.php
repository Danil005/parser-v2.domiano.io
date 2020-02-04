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
        return false;
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
        
    }

}