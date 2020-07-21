<?php

/**
 * @file Rooms.php
 * @author Danil Sidorenko
 * @description Выводит данные комнат
 */

namespace App\Dictionary;

class Rooms
{
    /**
     * Кол-во комнат
     *
     * @var int
     */
    private $rooms;

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

    const TYPE = [
        'one' => [
            '1 комнатная', "1", '1-комнатные', '1-к', '1-комн',
            '1 комната'
        ],
        'two' => [
            '2-х комнатная', "2", '2-комнатные', '2-к', '2-комн',
            '2 комнаты'
        ],
        'three' => [
            '3-х комнатная', "3", '3-комнатные', '3-к', '3-комн',
            '3 комнаты'
        ],
        'four' => [
            '4-х комнатная', "4", '4-комнатные', '4-к', '4-комн',
            '4 комнаты'
        ],
        'more' => [
            '5 комнатная', "5", '5-комнатные', '5-к', '5-комн',
            '5 комнат'
        ],
        'studio' => ['студия']
    ];

    public function parse($rooms, $house = false)
    {
        $rooms = mb_strtolower($rooms);
        if( $house )
            return intval($rooms);

        $ignore_number = false;
        if( strlen($rooms) > 3 ) $ignore_number = true;



        foreach(self::TYPE as $type=>$item) {
            foreach($item as $value) {
                if( $ignore_number && in_array($value, ['1', '2', '3', '4', '5']) )
                    continue;
                if( strpos($rooms, $value) !== false ) {
                    $method = $this->toCamelCase($type);
                    if( method_exists($this, $method) ) {
                        $this->rooms = $this->$method();
                        return $this->rooms; 
                    } else {
                        return '';
                    }
                }
            }
        }

        return "";
    }

    /**
     * Одна комната
     *
     * @return string
     */
    public function one()
    {
        return 'one_room';
    }

    /**
     * Две комнаты
     *
     * @return string
     */
    public function two()
    {
        return 'two_room';
    }

    /**
     * Три комнаты
     *
     * @return string
     */
    public function three()
    {
        return 'three_room';
    }

    /**
     * Четыре комнаты
     *
     * @return string
     */
    public function four()
    {
        return 'four_room';
    }

    /**
     * Много комнат
     *
     * @return string
     */
    public function more()
    {
        return 'more_room';
    }

    /**
     * Студия
     *
     * @return string
     */
    public function studio()
    {
        return 'studio';
    }
}