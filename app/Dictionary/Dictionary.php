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
     * Получить объект комнат
     *
     * @return Rooms
     */
    private function dictionaryRooms()
    {
        return new Rooms();
    }

    /**
     * Получить объект материал стен
     *
     * @return WallMaterial
     */
    private function dictionaryWallMaterial()
    {
        return new WallMaterial();
    }

    /**
     * Получить объект состояния ремонта квартиры/дома.
     *
     * @return ConditionObject
     */
    private function dictionaryConditionObject()
    {
        return new ConditionObject();
    }

    /**
     * Получить объект санузла.
     *
     * @return Wc
     */
    private function dictionaryWc()
    {
        return new Wc();
    }

    /**
     * Найти элемент
     *
     * @param $value
     * @param $needle
     * @param bool $old
     * @return bool
     */
    private function find($value, $needle, $old = false)
    {
        $temp = (string) $value;
        if( !$old ) {
            $value = explode(" ", $value);

            foreach ($value as $item) {
                if ($item == $needle) {
                    return true;
                }
            }
        }
        return strpos($temp, $needle) !== false;
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
            if ($this->find($value, $item)) {
                return true;
            }
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
        $find_section = ['category', 'title', 'type_object_other'];

        $section_name = "";

        foreach ($find_section as $item) {
            $val = mb_strtolower(preg_replace('/[^ a-zа-яё]/ui', '', $this->getValue($item)));

            if ($this->findWithArray($val, [
                'квартира', 'кв', 'студия', 'и', 'r', 'к'
            ])) {
                $section_name = 'apartment';
            }

            if ($this->findWithArray($val, [
                'дом', 'коттедж', 'дача', 'загородной'
            ])) {
                $section_name = 'house';
                $type_object = 'house';
            }

            if ($this->findWithArray($val, [
                'коттедж'
            ])) $type_object = 'cottage';

            if ($this->findWithArray($val, [
                'дача', 'загородной'
            ])) $type_object = 'country_house';

            if ($this->findWithArray($val, [
                'участок', 'земля', 'уч'
            ])) {
                $section_name = 'stead';
            }
        }

        if ($section_name == "") {
            if ($this->getValue('land_square')) {
                $section_name = 'house';
                if ($this->getValue('full_square') != 'участок')
                    $type_object = 'house';
            }
        }


        if ($section_name == "") {
            $section_name = 'apartment';
        }

        $this->callback($callback, $section_name);

        if (empty($section_name))
            throw new Exception('Not recognized section_name. Use callback function.');

        $this->section_name = $section_name;

        if (isset($type_object)) {
            return $section_name ? ['section_name' => $section_name, 'type_object' => $type_object] : "";
        }

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
        $phone = str_replace('-', '', $phone);
        $phone = preg_replace('/[()]/ui', '', $phone);

        if ($this->find($phone, " ", true)) {
            $temp = explode(" ", $phone)[0];
            if (mb_strlen($temp) > 7)
                $phone = $temp;
        }

        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("+", "", $phone);

        $phone = $this->find($phone, ",", true) ? explode(',', $phone)[0] : $phone;
        $phone = $this->find($phone, ';', true) !== false ? explode(';', $phone)[0] : $phone;
        $phone = $this->find($phone, '.', true) !== false ? explode('.', $phone)[0] : $phone;

        $phone = preg_replace('/\D/i', '', $phone);
        $phone = preg_replace('/^((8|7))?(\d{1,10})/i',
            '${3}', $phone);


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
        $name = $this->find($name, ', ', true) ? explode(', ', $name)[0] : $name;
        $name = $this->find($name, ',', true) ? explode(',', $name)[0] : $name;
        $name = $this->find($name, '; ', true) ? explode('; ', $name)[0] : $name;
        $name = $this->find($name, ';', true) ? explode(';', $name)[0] : $name;

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

        if ($this->find($price, 'тыс', true)) {
            $price = floatval($price);
            $price *= 1000;
        }

        $price = floatval($price);

        if (strlen($price) <= 5)
            $price *= 1000;

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

        $city = $this->getValue('city');
        $district = $this->getValue('district');
        $street = $this->getValue('street');

        if ($city) $address .= ', ' . $city;
        if ($district) $address .= ', ' . $district;
        if ($street) $address .= ', ' . $street;

        if ($address == "")
            $address = $this->getValue('title');

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

        if ($photos == null)
            return ['photos' => []];

        $photos = str_replace(' ', '', $photos);

        $photos = $this->find($photos, ',', true) ? explode(',', $photos) : $photos;
        $photos = $this->find($photos, ';', true) ? explode(';', $photos) : $photos;

        if (!is_array($photos))
            $photos = [$photos];

        $this->callback($callback, $photos);

        if (isset($photos[0]) && $photos[0] == '') {
            $photos = [];
        } elseif (empty($photos)) {
            $photos = [];
        }

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
        $house_storey = "";
        if ($this->section_name == 'stead') return '';

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

        $temp = str_replace(' ', '', $floor);
        $floor = $temp;

        if ($this->find($floor, '/', true)) {
            $house_storey = extractHouseStory($floor, '/', $section_name);
            $floor = intval($house_storey[1]);
            $house_storey = intval($house_storey[0]);
        } elseif ($this->find($floor, 'из', true)) {
            $house_storey = extractHouseStory($floor, 'из', $section_name);
            $floor = intval($house_storey[1]);
            $house_storey = intval($house_storey[0]);
        } else {
            if ($floor != "") {
                $floor = intval(preg_replace('/[\D]/ui', '', $floor));
            }
        }

        if ($floor == "") {
            if ($this->find($temp, '/', true)) {
                $floor = explode('/', $temp);
                $house_storey = intval($floor[1]);
                $floor = intval(preg_replace("/[а-яА-яa-zA-z]/ui", '', $floor[0]));
            }
        }

        if ($floor == "") {
            $title = $this->getValue('title');
            if ($this->find($title, ',', true))
                $title = explode(',', $title)[2];

            if ($this->find($title, '/', true)) {
                $floor = explode('/', $title);
                $house_storey = intval($floor[1]);
                $floor = intval(preg_replace("/[а-яА-яa-zA-z]/ui", '', $floor[0]));
            }
        }


        $this->callback($callback, ['floor' => $floor, 'house_storey' => $house_storey]);
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

    /**
     * Получить данные о комнатах.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function rooms($value = "", callable $callback = null)
    {
        $rooms = $value == "" ? $this->getValue('rooms') : $value;

        if ($this->section_name == 'stead') return '';

        if ($rooms != "") {
            if ($this->section_name == 'house')
                $rooms = $this->dictionaryRooms()->parse($rooms, true);
            else
                $rooms = $this->dictionaryRooms()->parse($rooms);
        }
        if ($rooms == "") {
            $title = $this->title();
            if (isset($title['title']))
                $title = $title['title'];
            if ($this->section_name == 'house')
                $rooms = $this->dictionaryRooms()->parse($title, true);
            else
                $rooms = $this->dictionaryRooms()->parse($title);
        }

        if ($rooms == "") {
            $category = $this->getValue('category');
            if ($this->section_name == 'house')
                $rooms = $this->dictionaryRooms()->parse($category, true);
            else
                $rooms = $this->dictionaryRooms()->parse($category);
        }

        $this->callback($callback, $rooms);
        if ($this->section_name == "apartment") {
            $key = 'type_object';
        } else {
            $key = 'rooms_amount';
        }

        return $rooms ? [$key => $rooms] : "";
    }

    /**
     * Получить материал стен.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function wallMaterial($value = "", callable $callback = null)
    {
        $wall_material = $value == "" ? $this->getValue('wall_material') : $value;
//        dd($this->getValue('wall_material'));
        $wall_material = $this->dictionaryWallMaterial()->parse($wall_material);

        if ($wall_material == "")
            $wall_material = $this->dictionaryWallMaterial()->parse($this->getValue('description'));

        $this->callback($callback, $wall_material);
        if ($this->section_name == "apartment") {
            $key = 'house_type';
        } else {
            $key = 'wall_material';
        }

        return $wall_material ? [$key => $wall_material] : "";
    }

    /**
     * Получить газоснабжение.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function gas($value = "", callable $callback = null)
    {
        $gas = $value == "" ? $this->getValue('gas') : $value;

        $gas = mb_strtolower($gas);

        $gas_bool = false;

        if ($gas == "нет")
            $gas_bool = false;
        elseif ($gas != "")
            $gas_bool = true;

        $this->callback($callback, $gas);

        return $gas ? ['gas' => $gas_bool] : "";
    }

    /**
     * Получить состояние ремонта.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function conditionObject($value = "", callable $callback = null)
    {
        $condition_object = $value == "" ? $this->getValue('condition_object') : $value;

        if ($this->section_name == 'stead') return '';

        $condition_object = mb_strtolower($condition_object);

        $condition_object = $this->dictionaryConditionObject()->parse($condition_object);

        $this->callback($callback, $condition_object);

        return $condition_object ? ['condition_object' => $condition_object] : "";
    }

    /**
     * Получить санузел.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function wc($value = "", callable $callback = null)
    {
        $wc = $value == "" ? $this->getValue('wc') : $value;

        if ($this->section_name == 'stead') return '';

        $wc = mb_strtolower($wc);

        $wc = $this->dictionaryWc()->parse($wc);

        $this->callback($callback, $wc);

        return $wc ? ['wc' => $wc] : "";
    }

    /**
     * Получить балкон.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function balcony($value = "", callable $callback = null)
    {
        $balcony = $value == "" ? $this->getValue('balcony') : $value;

        if ($this->section_name == 'stead') return '';

        $balcony = mb_strtolower($balcony);

        $balcony_bool = false;

        if ($balcony == "нет")
            $balcony_bool = false;
        elseif ($balcony != "")
            $balcony_bool = true;

        $this->callback($callback, $balcony);

        return $balcony ? ['balcony' => $balcony_bool] : "";
    }

    /**
     * Получить кол-во этажей всего.
     * Если используете $callback, то чтобы получить
     * форматированный номер телефона используйте функцию
     * func_get_args() - array
     *
     * @param string $value
     * @param callable|null $callback
     * @return mixed
     */
    public function houseStorey($value = "", callable $callback = null)
    {
        $house_storey = $value == "" ? $this->getValue('house_storey') : $value;
        if ($this->section_name == 'stead') return '';

        if ($house_storey == "") {
            // Проверка для Cian-дома
            $title = $this->title();
            if (isset($title['title']))
                $title = $title['title'];
            if ($this->find($title, '-этажный', true)) {
                $house_storey = explode('-этажный', $title)[0];
            }
        }

        if ($house_storey == "") {
            $title = $this->title();
            if (isset($title['title']))
                $title = $title['title'];

            if ($this->find($title, 'эт', true)) {
                $title = str_replace(" ", "", $title);
                if ($this->find($title, ',', true)) {
                    $house_storey = explode(',', $title)[2];
                    if ($this->find($house_storey, '/', true)) {
                        $house_storey = str_replace("эт.", "", explode('/', $house_storey)[1]);
                    }
                }
            }
        }

        $house_storey = mb_strtolower($house_storey);

        $house_storey = intval($house_storey);

        $this->callback($callback, $house_storey);

        return $house_storey ? ['house_storey' => $house_storey] : "";
    }
}