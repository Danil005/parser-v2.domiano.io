<?php

/**
 * @file WallMaterial.php
 * @author Danil Sidorenko
 * @description Материал дома
 */

namespace App\Dictionary;

class WallMaterial
{
    /**
     * Материал дома
     *
     * @var string
     */
    private $wall_material;

    /**
     * Русское наименования (варианты)
     */
    const TYPE = [
        'brick' => ['кирпичный', 'кирпич', 'кир'],
        'monolithic' => ['монолитный', 'мон'],
        'block' => ['блочный'],
        'wood' => ['деревянный', 'брус'],
        'panel' => ['панельный', 'пан'],
        'cinder_block' => ['шлакоблок'],
        'aerated_concrete' => ['газобетон'],
        'brick_aerocrete' => ['кирпичный газобетон']
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
     * Получить тип дома
     *
     * @param $wall_material
     * @return int|string
     */
    public function parse($wall_material)
    {
        $wall_material = mb_strtolower($wall_material);
        foreach(self::TYPE as $type=>$item) {
            foreach($item as $value) {
                if( $wall_material == $value ) {
                    $this->wall_material = $this->{$this->toCamelCase($type)}();
                    return $this->wall_material;
                }
            }
        }

        return "";
    }

    /**
     * Кирпичный
     *
     * @return string
     */
    public function brick()
    {
        return 'brick';
    }

    /**
     * Панельный
     *
     * @return string
     */
    public function panel()
    {
        return 'panel';
    }

    /**
     * Блочный
     *
     * @return string
     */
    public function block()
    {
        return 'block';
    }

    /**
     * Монолитный
     *
     * @return string
     */
    public function monolithic()
    {
        return 'monolithic';
    }

    /**
     * Деревянный
     *
     * @return string
     */
    public function wood()
    {
        return 'wood';
    }
}