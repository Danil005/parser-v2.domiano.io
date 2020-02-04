<?php 

/**
 * @file CrystalSource.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface; 
use App\Traits\SourceTrait; 
use Exception; 

class CrystalSource implements SourceInterface
{
    use SourceTrait; 

    /**
     * Устновить source_id
     * 
     * @return string
     */
    protected function sourceId()
    {
        return 'crystal';
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
        $square = $this->getValue('full_square');
        if( strpos($square, '/') ) {
            $square = explode('/', $square);

            if( isset($square[0]) ) {
                $full_square = str_replace(',', '.', $square[0]);
                $this->return_data[] = ['full_square' => floatval($full_square)];
            }

            if( isset($square[1]) ) {
                $living_square = str_replace(',', '.', $square[1]);
                $this->return_data[] = ['living_square' => floatval($living_square)];
            }

            if( isset($square[2]) ) {
                $kitchen_square = str_replace(',', '.', $square[2]);
                $this->return_data[] = ['kitchen_square' => floatval($kitchen_square)];
            }
        }
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