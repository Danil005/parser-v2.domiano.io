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

    public $not_response;

    protected $return_data_temp;

    /**
     * Проверка что был загружен объект массивов
     *
     * @var int
     */
    protected $is_object = 0;

    /**
     * SourceTrait constructor.
     * @param $data
     * @param int $is_object
     */
    public function __construct($data, $is_object = 0)
    {
        $fields = fields();
        $this->is_object = $is_object;

        $this->source_id = $this->sourceId();
        $this->fields = isset($fields[$this->source_id]) ? $fields[$this->source_id] : [];
        $this->data = $data;

        $this->filtered_data = $this->filterData();
        if ($this->getValue('link') != "")
            $this->return_data[] = ['link' => $this->getValue('link')];

        if( $this->getValue('deadline') != "" )
            $this->return_data[] = ['property_type' => 'new_building'];

        $land_square = $this->getValue('land_square');
        if ($land_square)
            $this->return_data[] = ['land_square' => floatval(str_replace(',', '.', $land_square))];



        $this->dictionary = new Dictionary($this->filtered_data);



        if (!$this->offDefaultFunctions())
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

            $this->return_data_temp = $temp;

            if( $temp['section_name'] == 'stead' ) {
                if( isset($temp['living_square']) ) unset($temp['living_square']);
                if( isset($temp['kitchen_square']) ) unset($temp['kitchen_square']);
                if( isset($temp['wall_material']) ) unset($temp['wall_material']);

                if( isset($temp['full_square']) ) $temp['land_square'] = $temp['full_square'];

                if( isset($temp['land_square']) ) unset($temp['full_square']);
            }

            if( $temp['section_name'] == 'stead' || $temp['section_name'] == 'house' ) {
                if( isset($temp['title']) ) {
                    $title = mb_strtolower($temp['title']);
                    if( strpos($title, 'сот') !== false ) {
                        $land_square = str_replace(',', '.', explode('сот', $title)[0]);
                        $land_square = explode(' ', $land_square);
                        $land_square = array_reverse($land_square);
                        $land_square = floatval(preg_replace('#[^0-9.,]#', '', $land_square[1]));
                        $temp['land_square'] = $land_square;
                    }
                }
            }

            $this->return_data = $temp;

            if( !$this->is_object ) {
                if (!$this->not_response)
                    echo response()->success()->setMessage('Successfully formatted data')
                        ->setData($this->return_data)->send();
            } else {
                return $this->return_data;
            }
        } else {
            if (!$this->not_response)
                echo response()->error()->setMessage('Data was not processed')
                    ->setData([])->send();
        }

    }

    /**
     * Получить результат работы
     *
     * @return array
     * @throws MessageNotExistException
     */
    public function getResult()
    {
        $this->is_object = 1;
        if (isset($this->return_data)) {

            $this->return_data[] = $this->extractSquare();
            $temp = [];

            foreach ($this->return_data as $value) {
                if (is_array($value))
                    foreach ($value as $field => $data) {
                        $temp[$field] = $data;
                    }
            }


            $this->return_data_temp = $temp;

            if( $temp['section_name'] == 'stead' ) {
                if( isset($temp['living_square']) ) unset($temp['living_square']);
                if( isset($temp['kitchen_square']) ) unset($temp['kitchen_square']);
                if( isset($temp['wall_material']) ) unset($temp['wall_material']);

                if( isset($temp['full_square']) ) $temp['land_square'] = $temp['full_square'];

                if( isset($temp['land_square']) ) unset($temp['full_square']);
            }

            if( $temp['section_name'] == 'stead' || $temp['section_name'] == 'house' ) {
                if( isset($temp['title']) ) {
                    $title = mb_strtolower($temp['title']);
                    if( strpos($title, 'сот') !== false ) {
                        $land_square = str_replace(',', '.', explode('сот', $title)[0]);
                        $land_square = explode(' ', $land_square);
                        $land_square = array_reverse($land_square);
                        $land_square = floatval(preg_replace('#[^0-9.,]#', '', $land_square[1]));
                        $temp['land_square'] = $land_square;
                    }
                }
            }

            $this->return_data = $temp;

            return $this->return_data;

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
        if ($full_square == 'not created' && $full_square != false) {
            echo response()->error()
                ->setMessage(
                    'Function fullSquare not created! Create this function, if you do not ' .
                    'use this function, please, return false'
                )
                ->send();
            return $this->not_response = true;
        }
        $living_square = $this->livingSquare();
        if ($living_square == 'not created' && $living_square != false) {
            echo response()->error()
                ->setMessage(
                    'Function livingSquare not created! Create this function, if you do not ' .
                    'use this function, please, return false'
                )
                ->send();
            return $this->not_response = true;
        }
        $kitchen_square = $this->kitchenSquare();
        if ($kitchen_square == 'not created' && $kitchen_square != false) {
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
            'photos', 'constructionYear', 'floor', 'houseStorey', 'rooms', 'wallMaterial', 'gas',
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

        $fieldsSource = $this->getFields();

        $fieldsSource = array_reverse($fieldsSource);

        foreach ($fieldsSource as $field => $titles) {
            if (is_array($titles)) {
                foreach ($titles as $item) {
                    foreach ($data as $field_data => $value) {
                        if ($item == $field_data && !isset($filtered_date[$field]))
                            $filtered_date[$field] = $value;
                    }
                }
            } else {
                foreach ($data as $field_data => $value) {
                    if ($titles == $field_data && !isset($filtered_date[$field]))
                        $filtered_date[$field] = $value;
                }
            }
        }


        foreach ($fields as $source) {
            $source = array_reverse($source);
            foreach ($source as $field => $title) {

                if (is_array($title)) {
                    foreach ($title as $item) {
                        foreach ($data as $field_data => $value) {
                            if (!array_key_exists($field, $filtered_date) && $value != "" && $item == $field_data)
                                $filtered_date[$field] = $value;
                        }
                    }
                } else {
                    foreach ($data as $field_data => $value) {
                        if (!array_key_exists($field, $filtered_date) && $value != "" && $title == $field_data)
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
        return isset($this->filtered_data[$value]) ? $this->filtered_data[$value] : "";
    }


    /**
     * Выполнить функции по умолчанию, указанные в
     * defaultFunctions()
     */
    public function callDefaultFunctions()
    {
        $default = $this->defaultFunctions();

        $this->return_data[] = ['deal_type' => 'sale'];
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