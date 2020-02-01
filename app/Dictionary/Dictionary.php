<?php

/**
 * @file Dictionary.php
 * @author Danil Sidorenko
 * @description Форматирование данных для формирования ответа
 */

namespace App\Dictionary;

use Exception;

class Dictionary
{
    /**
     * Фильтрованные данные
     *
     * @var array
     */
    private $filtered_data = [];


    /**
     * Тип объекта
     *
     * @var string
     */
    private $section_name = '';

    public function __construct($filtered_data)
    {
        $this->filtered_data = $filtered_data;
    }

    /**
     * Найти элемент
     *
     * @param $value
     * @param $needle
     * @return bool
     */
    private function find($value, $needle)
    {
        return strpos($value, $needle) !== false;
    }

    /**
     * Найти элемент с помощью массива
     *
     * @param $value
     * @param $needle
     * @return bool
     */
    private function findWithArray($value, $needle)
    {
        foreach ($needle as $item) {
            if ($this->find($value, $item) !== false)
                return true;
        }

        return false;
    }

    /**
     * Получить значение из фильтрованных данных.
     *
     * @param $value
     * @return mixed
     */
    private function getValue($value)
    {
        return $this->filtered_data[$value];
    }

    /**
     * Выполнить Callback, если суещствует.
     *
     * @param callable $callback
     * @param $value
     */
    private function callback(callable $callback = null, $value = null)
    {
        if ($callback != null)
            call_user_func($callback, $value);
    }


    /**
     * Получить тип объекта.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     * @throws Exception
     */
    public function sectionName($value = "", callable $callback = null)
    {
        $find_section = ['title'];


        foreach ($find_section as $item) {
            $val = preg_replace('/[^ a-zа-яё]/ui', '', $this->getValue($item));
            if ($this->findWithArray($val, [
                'квартира', 'кв', 'студия'
            ])) $section_name = 'apartment';

            if ($this->findWithArray($val, [
                'дом', 'коттедж', 'таунхаус', 'дача'
            ])) $section_name = 'house';

            if ($this->findWithArray($val, [
                'участок', 'земля'
            ])) $section_name = 'stead';
        }

        if (empty($section_name))
            throw new Exception('Not recognized section_name.');

        $this->section_name = $section_name;
        $this->callback($callback, $section_name);

        return $section_name ? ['section_name' => $section_name] : "";
    }

    /**
     * Получить номер телефона.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function phone($value = "", callable $callback = null)
    {
        $phone = $value == "" ? $this->getValue('owner_phone') : $value;
        $phone = trim($phone);

        if ($this->find($phone, " ")) {
            $temp = explode(" ", $phone)[0];
            if (mb_strlen($temp) > 7)
                $phone = $temp;
        }

        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("+", "", $phone);

        $phone = $this->find($phone, ",") ? explode(',', $phone)[0] : $phone;
        $phone = $this->find($phone, ';') !== false ? explode(';', $phone)[0] : $phone;
        $phone = $this->find($phone, '.') !== false ? explode('.', $phone)[0] : $phone;

        $this->callback($callback, $phone);

        return $phone ? ['owner_phone' => floatval($phone)] : "";
    }

    /**
     * Получить имя.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function name($value = "", callable $callback = null)
    {
        $name = $value == "" ? $this->getValue('owner_name') : $value;

        $name = trim($name);
        $name = $this->find($name, ', ') ? explode(', ', $name)[0] : $name;
        $name = $this->find($name, ',') ? explode(',', $name)[0] : $name;
        $name = $this->find($name, '; ') ? explode('; ', $name)[0] : $name;
        $name = $this->find($name, ';') ? explode(';', $name)[0] : $name;

        $this->callback($callback, $name);

        return $name ? ['owner_name' => $name] : "";
    }

    /**
     * Получить название объявления.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function title($value = "", callable $callback = null)
    {
        $title = $value == "" ? $this->getValue('title') : $value;
        $title = trim($title);

        $this->callback($callback, $title);

        return $title ? ['title' => $title] : "";
    }

    /**
     * Получить цену объекта.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function price($value = "", callable $callback = null)
    {
        $price = $value == "" ? $this->getValue('price') : $value;
        $price = trim($price);
        $price = str_replace(" ", "", $price);
        $price = floatval($price);

        $this->callback($callback, $price);

        return $price ? ['price' => $price] : "";
    }

    /**
     * Получить адрес объекта.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function address($value = "", callable $callback = null)
    {
        $address = $value == "" ? $this->getValue('address') : $value;
        $address = trim($address);

        $this->callback($callback, $address);

        return $address ? ['address' => $address] : "";
    }

    /**
     * Получить описание объекта.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function description($value = "", callable $callback = null)
    {
        $description = $value == "" ? $this->getValue('description') : $value;
        $description = trim($description);

        $this->callback($callback, $description);

        return $description ? ['description' => $description] : "";
    }

    /**
     * Получить изображения объекта.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param array $value
     * @param callable|null $callback
     * @return mixed
     */
    public function photos($value = [], callable $callback = null)
    {
        $photos = $value == [] ? $this->getValue('photos') : $value;

        $photos = str_replace(' ', '', $photos);

        $photos = $this->find($photos, ',') ? explode(',', $photos) : $photos;
        $photos = $this->find($photos, ';') ? explode(';', $photos) : $photos;

        $this->callback($callback, $photos);

        return $photos ? ['photos' => $photos] : [];
    }

    /**
     * Получить год постройки объекта.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function constructionYear($value = "", callable $callback = null)
    {
        $construction_year = $value == "" ? $this->getValue('construction_year') : $value;
        $construction_year = trim($construction_year);

        $construction_year = intval($construction_year);
        $this->callback($callback, $construction_year);

        return $construction_year ? ['construction_year' => $construction_year] : "";
    }

    /**
     * Получить этажность дома.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function floor($value = "", callable $callback = null)
    {
        $section_name = $this->section_name;

        function extractHouseStory($value, $delimiter, $section_name)
        {
            $floor = explode($delimiter, $value);

            $house_storey = "";

            if ($section_name == 'apartment') {
                $house_storey = $floor[1];
            }

            return [$house_storey, $floor[0]];
        }

        $floor = $value == "" ? $this->getValue('floor') : $value;

        $floor = str_replace(' ', '', $floor);

        if ($this->find($floor, '/')) {
            $house_storey = extractHouseStory($floor, '/', $section_name);
            $floor = intval($house_storey[1]);
            $house_storey = intval($house_storey[0]);
        } elseif ($this->find($floor, 'из')) {
            $house_storey = extractHouseStory($floor, 'из', $section_name);
            $floor = intval($house_storey[1]);
            $house_storey = intval($house_storey[0]);
        } else {
            if ($floor != "") {
                $floor = intval(preg_replace('/[\D]/ui', '', $floor));
            }
        }

        $this->callback($callback, $floor);
        if ($section_name == 'apartment') {
            $key = 'floor';
        } else {
            $key = 'house_storey';
        }

        if (!empty($house_storey)) {
            $floor = $floor ? [$key => $floor, 'house_storey' => $house_storey] : "";
        } else {
            $floor = $floor ? [$key => $floor] : "";
        }

        return $floor;
    }


}