<?php

/**
 * @file Wc.php
 * @author Danil Sidorenko
 * @description Состояние квартиры/дома
 *
 */

namespace App\Dictionary;

class Wc
{
    /**
     * Санузел
     *
     * @var string
     */
    private $wc;

    /**
     * Русское наименования (варианты)
     */
    const TYPE = [
        'separated' => ['раздельный', 'отдельная', "2", 'разд'],
        'combined' => ['совмещенный', 'сдел', 'совм'],
        'absent' => ['отсутствует']
    ];

    /**
     * Преобразовать в CamelCase.
     *
     * @param $key
     * @return mixed
     */
    private function toCamelCase($key)
    {
        return lcfirst(str_replace('_', '', ucwords($key, '_')));
    }

    /**
     * Получить тип санузла
     *
     * @param $wc
     * @return int|string
     */
    public function parse($wc)
    {
        $wc = mb_strtolower($wc);

        foreach(self::TYPE as $type=>$item) {
            foreach($item as $value) {
                if( $wc == $value ) {
                    $this->wc = $this->{$this->toCamelCase($type)}();
                    return $this->wc;
                }
            }
        }

        return "";
    }

    /**
     * Раздельный
     *
     * @return string
     */
    public function separated()
    {
        return 'separated';
    }

    /**
     * Совмещенный
     *
     * @return string
     */
    public function combined()
    {
        return 'combined';
    }

    /**
     * Отсутвует
     *
     * @return string
     */
    public function absent()
    {
        return 'absent';
    }
}