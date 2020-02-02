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

    private $not_response;


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

        if(!$this->offDefaultFunctions())
            $this->callDefaultFunctions();
    }

    /**
     * SourceTrait destruct
     *
     * @throws MessageNotExistException
     */
    public function __destruct()
    {
        if (isset($this->return_data)) {

            $this->return_data[] = $this->extractSquare();
            $temp = [];

            foreach ($this->return_data as $value) {
                if (is_array($value))
                    foreach ($value as $field => $data) {
                        $temp[$field] = $data;
                    }
            }
            $this->return_data = $temp;

            if (!$this->not_response)
                echo response()->success()->setMessage('Successfully formatted data')
                    ->setData($this->return_data)->send();
        } else {
            if (!$this->not_response)
                echo response()->error()->setMessage('Data was not processed')
                    ->setData([])->send();
        }
    }

    /**
     * Получить площади дома/квартиры/участка
     * Если не нужно реализовывать, то необходимо создать функцию
     * в главном глассе Source и вернуть false
     *
     *
     * @return mixed
     * @throws MessageNotExistException
     */
    private function extractSquare()
    {
        $full_square = $this->fullSquare();
        if ($full_square == 'not created') {
            echo response()->error()
                ->setMessage(
                    'Function fullSquare not created! Create this function, if you do not ' .
                    'use this function, please, return false'
                )
                ->send();
            return $this->not_response = true;
        }
        $living_square = $this->livingSquare();
        if ($living_square == 'not created') {
            echo response()->error()
                ->setMessage(
                    'Function livingSquare not created! Create this function, if you do not ' .
                    'use this function, please, return false'
                )
                ->send();
            return $this->not_response = true;
        }
        $kitchen_square = $this->kitchenSquare();
        if ($kitchen_square == 'not created') {
            echo response()->error()
                ->setMessage(
                    'Function kitchenSquare not created! Create this function, if you do not ' .
                    'use this function, please, return false'
                )
                ->send();
            return $this->not_response = true;
        }

        $data = [];

        if ($full_square != "")
            $data['full_square'] = $full_square;
        if ($living_square != "")
            $data['living_square'] = $living_square;
        if ($kitchen_square != "")
            $data['kitchen_square'] = $kitchen_square;

        return $data;
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
     * Список функций по умолчнаю.
     * Для получения данных.
     *
     * @return array
     */
    protected function defaultFunctions()
    {
        return [
            'sectionName', 'title', 'name', 'phone', 'price', 'address', 'description',
            'photos', 'constructionYear', 'floor', 'rooms', 'wallMaterial', 'gas',
            'conditionObject', 'wc', 'balcony'
        ];
    }

    /**
     * Отключить выполнения функций по умолчанию.
     *
     * @return bool
     */
    protected function offDefaultFunctions()
    {
        return false;
    }

    /**
     * Получить всю площадь
     *
     * @return string
     */
    protected function fullSquare()
    {
        return 'not created';
    }

    /**
     * Получить жилую площадь
     *
     * @return string
     */
    protected function livingSquare()
    {
        return 'not created';
    }

    /**
     * Получить площадь кухни
     *
     * @return string
     */
    protected function kitchenSquare()
    {
        return 'not created';
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
     * Выполнить функции по умолчанию, указанные в
     * defaultFunctions()
     */
    public function callDefaultFunctions()
    {
        $default = $this->defaultFunctions();

        foreach ($default as $function) {
            $this->return_data[] = $this->dictionary->$function();
        }
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