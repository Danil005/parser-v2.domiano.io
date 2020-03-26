<?php

/**
 * @file DomRioSource.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface;
use App\Traits\SourceTrait;
use Exception;

class DonRioSource implements SourceInterface
{
    use SourceTrait;

    /**
     * Устновить source_id
     *
     * @return string
     */
    protected function sourceId()
    {
        return 'donrio';
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

        return floatval(str_replace('.', ',', $square));
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
        $this->return_data[] = ['deal_type' => 'sale'];

        $land_square = $this->getValue('land_square');
        if ($land_square)
            $this->return_data[] = ['land_square' => floatval(str_replace(',', '.', $land_square))];

        $section_name = mb_strtolower($this->getValue('full_square'));
        if( $section_name === 'участок' ) {
            $this->return_data[] = ['section_name' => 'stead'];
            array_pop($this->return_data[1]);
        }

        $this->return_data[] = ['owner_name' => $this->extractOwnerName()];
    }

    private function extractOwnerName()
    {
        $contact = $this->getValue('owner_phone');
        $re = '/([0-9\-\(\)\s]+)(.*$)/mu';
        preg_match_all($re, $contact, $matches, PREG_SET_ORDER, 0);

        return isset($matches[0][2]) ? $matches[0][2] : "";
    }

}