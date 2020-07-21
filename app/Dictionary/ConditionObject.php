<?php

/**
 * @file ConditionObject.php
 * @author Danil Sidorenko
 * @description Состояние квартиры/дома
 *
 */

namespace App\Dictionary;

class ConditionObject
{
    /**
     * Состояние квартиры/дома
     *
     * @var string
     */
    private $condition_object;

    /**
     * Русское наименования (варианты)
     */
    const TYPE = [
        'is_being_built' => ['строиться'],
        'needs_redecoration' => ['нуждается в ремонте'],
        'normal_repair' => ['нормальный ремонт','хорошее', 'хор', 'с отделкой'],
        'eurorepair' => ['евроремонт', 'евро'],
        'built_to_finishing_strokes' => ['построен для финишных шрихов', 'чистовая'],
        'design_repair' => ['дизайнерский ремонт'],
        'need_repair' => ['нужен ремонт', 'без ремонта']
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
     * @param $condition_object
     * @return int|string
     */
    public function parse($condition_object)
    {
        $condition_object = mb_strtolower($condition_object);

        foreach(self::TYPE as $type=>$item) {
            foreach($item as $value) {
                if( $condition_object == $value ) {
                    $method = $this->toCamelCase($type);
                    if( method_exists($this, $method) ) {
                        $this->condition_object = $this->$method();
                        return $this->condition_object;
                    } else {
                        return '';
                    }
                }
            }
        }

        return "";
    }

    /**
     * Строиться
     *
     * @return string
     */
    public function isBeingBuilt()
    {
        return 'is_being_built';
    }

    /**
     * Нуждается в ремонте
     *
     * @return string
     */
    public function needsRedecoration()
    {
        return 'needs_redecoration';
    }

    /**
     * Нормальный ремонт
     *
     * @return string
     */
    public function normalRepair()
    {
        return 'normal_repair';
    }

    /**
     * Евроремонт
     *
     * @return string
     */
    public function eurorepair()
    {
        return 'eurorepair';
    }

    /**
     * Сделан для финишных штрихов
     *
     * @return string
     */
    public function builtToFinishingStrokes()
    {
        return 'built_to_finishing_strokes';
    }

    /**
     * Дизайнерский ремонт
     *
     * @return string
     */
    public function designRepair()
    {
        return 'design_repair';
    }

    /**
     * Нужен ремонт
     *
     * @return string
     */
    public function needRepair()
    {
        return 'need_repair';
    }
}