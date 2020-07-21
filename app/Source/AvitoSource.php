<?php

/**
 * @file AvitoSource.php
 * @author Danil Sidorenko
 * @description Source для обработки сервиса
 */

namespace App\Source;

use App\Interfaces\SourceInterface;
use App\Traits\SourceTrait;
use Exception;

class AvitoSource implements SourceInterface
{
    use SourceTrait;

    /**
     * Устновить source_id
     *
     * @return string
     */
    protected function sourceId()
    {
        return 'avito';
    }

    /**
     * Спарсить полную площадь
     * Если оставить false, то парсить не будет.
     * Если убрать return false, то нужно будет реализовать метод и
     * вернуть значение.
     *
     * @return mixed
     * @throws Exception
     */
    protected function fullSquare()
    {
        $square = $this->getValue('title');

        $square = explode(',', $square)[1];

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
        $square = $this->getValue('living_square');

        $square = str_replace(',', '.', $square);

        return floatval($square);
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
        $square = $this->getValue('kitchen_square');

        $square = str_replace(',', '.', $square);

        return floatval($square);
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
        $type_advert = mb_strtolower($this->getValue('type_advert'));

        if (strpos($this->getValue('title'), 'Дом') !== false) {
            $this->return_data[] = ['section_name' => 'house'];
            if (mb_strtolower($this->getValue('category')) == 'коттедж')
                $this->return_data[] = ['type_object' => 'cottage'];
            $this->return_data[] = ['distance_to_city' => ($this->getValue('distance'))];


            $square = $this->getValue('title');

            $square = explode('м²', $square)[0];
            $this->return_data[] = ['full_square' => floatval(str_replace('Дом', "", $square))];

            if ($this->getValue('house_storey') != "")
                $this->return_data[] = ['house_storey' => intval($this->getValue('house_storey'))];
        }

        $section_name = $this->dictionary->sectionName()['section_name'];
        if ($type_advert != "" && ($type_advert == 'вторичка'))
            $this->return_data[] = ['property_type' => 'secondary'];

        if ($section_name == 'stead')
            $this->return_data[] = ['land_category' => $this->relationLandCategory($this->getValue('title'))];
    }

    protected function relationLandCategory($landCategory)
    {
        $landCategory = mb_strtolower($landCategory);

        if (strpos($landCategory, 'ижс') !== false)
            return 'settlement';
        elseif (strpos($landCategory, 'промназначения') !== false)
            return 'industrial_purpose';
        else
            return 'agricultural_purpose';
    }

}