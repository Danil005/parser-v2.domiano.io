<?php

/**
 * @file YoulaSource.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface;
use App\Traits\SourceTrait;
use Exception;

class YoulaSource implements SourceInterface
{
    use SourceTrait;

    /**
     * Устновить source_id
     *
     * @return string
     */
    protected function sourceId()
    {
        return 'youla';
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

        if ($square == "") {
            $title = mb_strtolower($this->getValue('title'));
            if (strpos($title, ',') !== false)
                if (strpos($title, '-') !== false)
                    if (strpos($title, 'квартира') !== false) {
                        $square = explode('-', explode(', ', $title)[2])[0];;
                    } else {
                        $square = explode('-', explode(', ', $title)[1])[0];
                    }
        }
        $square = str_replace(',', '.', $square);

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

    }

}