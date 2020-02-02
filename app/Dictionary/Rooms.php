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

    private function type()
    {
        switch ($this->rooms) {
            case 'студия':
                return $this->studio();
                break;

            case 1:
                return $this->one();
                break;
            case 2:
                return $this->two();
                break;
            case 3:
                return $this->three();
                break;
            case 4:
                return $this->four();
                break;
            default:
                if( $this->rooms > 4 )
                    return $this->more();
                else
                    return "";
                break;
        }
    }

    public function parse($rooms, $other_field = false)
    {
        if( !$other_field ) {
            if( $rooms == 'студия' ) {
                return $this->studio();
            }

            $rooms = $this->type();

            $this->rooms = $rooms;
            return $rooms;
        } else {
            if( $rooms == 'студия' ) {
                return $this->studio();
            }
            $this->rooms = intval($rooms);
            return $this->type();
        }
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