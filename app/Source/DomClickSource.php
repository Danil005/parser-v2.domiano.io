<?php 

/**
 * @file DomClickSource.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface; 
use App\Traits\SourceTrait; 
use Exception; 

class DomClickSource implements SourceInterface
{
    use SourceTrait; 

    /**
     * Устновить source_id
     * 
     * @return string
     */
    protected function sourceId()
    {
        return 'domclick';
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
        $title = $this->getValue('title');

        $square = str_replace(',', '.', explode(', ', $title)[1]);

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
        $cat = mb_strtolower($this->getValue('cat_3'));
        if( strpos($cat, 'вторичного') !== false )
            $this->return_data[] = ['property_type' => 'secondary'];
    }

}