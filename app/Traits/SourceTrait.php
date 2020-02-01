<?php

/**
 * @file SourceTrait.php
 * @author Danil Sidorenko
 * @description Трейт для работы с Source серверисов
 */

namespace App\Traits;

use App\Dictionary\Dictionary;
use App\Exceptions\MessageNotExistException;

trait SourceTrait
{
    /**
     * ID source-сервиса
     *
     * @var string
     */
    public $source_id;

    /**
     * Поля Source
     *
     * @var array
     */
    private $fields;

    /**
     * Массив полученный из Body.
     *
     * @var array
     */
    private $data;

    /**
     * Фильтрованные данные из $data
     *
     * @var array
     */
    private $filtered_data;

    /**
     * Форматирование данных для формирования ответа
     *
     * @var Dictionary
     */
    protected $dictionary;


    protected $return_data;


    /**
     * SourceTrait constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $fields = fields();

        $this->source_id = $this->sourceId();
        $this->fields = isset($fields[$this->source_id]) ? $fields[$this->source_id] : [];
        $this->data = $data;

        $this->filtered_data = $this->filterData();

        $this->dictionary = new Dictionary($this->filtered_data);
    }

    /**
     * SourceTrait destruct
     *
     * @throws MessageNotExistException
     */
    public function __destruct()
    {
        if( isset($this->return_data) ) {
            $temp = [];

            foreach ($this->return_data as $value) {
                if (is_array($value))
                    foreach ($value as $field => $data) {
                        $temp[$field] = $data;
                    }
            }
            $this->return_data = $temp;
            echo response()->success()->setMessage('Successfully formatted data')
                ->setData($this->return_data)->send();
        }
    }


    /**
     * Установить source_id
     *
     * @return string
     */
    protected function sourceId()
    {
        return '';
    }

    /**
     * Получить поля
     *
     * @return array
     */
    protected function getFields()
    {
        return $this->fields;
    }

    /**
     * Получить все поля.
     *
     * @return array
     */
    protected function getFieldsAll()
    {
        return fields();
    }

    /**
     * Получить массив полученный с Body
     *
     * @return array
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Филтровать данные, которые пришли с Body
     *
     * @return array
     */
    protected function filterData()
    {
        $data = $this->getData();
        $fields = $this->getFieldsAll();

        $filtered_date = [];

        /**
         * Фильтрация по всем fields
         */
        foreach ($data as $field_data => $value) {
            foreach ($fields as $source) {
                foreach ($source as $field => $title) {
                    if (is_array($title)) {
                        foreach ($title as $item) {
                            if ($item == $field_data)
                                $filtered_date[$field] = $value;
                        }
                    } else {
                        if ($title == $field_data)
                            $filtered_date[$field] = $value;
                    }
                }
            }
        }

        return $filtered_date;
    }

    /**
     * Получить значение из фильтрованных данных.
     *
     * @param $value
     * @return mixed
     */
    protected function getValue($value)
    {
        return $this->filtered_data[$value];
    }

    /**
     * Реализация source
     *
     * @return mixed
     * @throws MessageNotExistException
     */
    public function call()
    {
        return response()->error()
            ->setMessage('The logic of the class is not described. Please, create call() function.')
            ->send();
    }
}