<?php

/**
 * @file fields.php
 * @author Danil Sidorenko
 * @description Файл для определения столбцов из Excel
 */

return [
    'cian' => [
        'link' => ['url', 'URL'],
        'title' => 'Название объявления',
        'price' => 'Цена',
        'address' => 'Адрес',
        'owner_phone' => 'Телефон',
        'description' => 'Описание',
        'photos' => ['Фото', 'Изображения'],
        'construction_year' => ['Построен', 'Год постройки'],
        'floor' => ['Этажи', 'Этаж'],
        'kitchen_square' => 'Кухня',
        'living_square' => 'Жилая',
        'full_square' => 'Общая',
        'wall_material' => ['Здание', 'Тип дома'],
        'gas' => 'Газоснабжение',
        'rooms' => 'Комнат',
        'deadline' => 'Срок сдачи',
        'condition_object' => ['Состояние ремонта', 'Ремонт'],
        'wc' => 'Санузел',
        'balcony' => ['Балкон', 'Количество балконов']
    ],
    'domclick' => [
        'link' => ['URL'],
        'title' => ['Название объявления', 'Announcement'],
        'owner_name' => ['Собственник', 'Name'],
        'price' => ["Цена", "Price"],
        'description' => ['Описание', "Description"],
        'owner_phone' => ['Телефон', 'Number'],
        'address' => 'Адрес',
        'photos' => ['Фото', "Images"],
        'rooms' => ['Комнат'],
        'living_square' => ['Жилая'],
        'condition_object' => ['Состояние ремонта', 'Ремонт'],
        'floor' => 'Этаж',
        'house_storey' => ['Количество этажей', 'Этажей'],
        'wall_material' => ['Материал стен']
    ],
    'domofon' => [
        'link' => ['Ссылка'],
        'title' => ['Название объявления'],
        'address' => 'Адрес',
        'description' => 'Описание',
        'owner_phone' => 'Телефон',
        'photos' => 'Фото',
        'rooms' => ['Комнаты'],
        'full_square' => 'Площадь',
        'kitchen_square' => 'Площадь кухни (м²)',
        'living_square' => 'Жилая площадь (м²)',
        'wall_material' => 'Материал здания',
        'house_storey' => 'Этажность'
    ],
    'donrio' => [
        'rooms' => 'ком.',
        'address' => 'Адрес',
        'floor' => 'Эт.',
        'description' => 'Хар',
        'owner_phone' => 'Тел контанк',
        'land_square' => 'Sуч.Всотках',
    ],
    'emls' => [
        'rooms' => 'Кол-во комнат',
        'price' => 'Цена',
        'full_square' => ['Общая пл.', 'Общая площадь'],
        'condition_object' => 'Готовность',
        'living_square' => 'Жилая пл.',
        'kitchen_square' => 'Кухня',
        'wall_material' => ['Здание', 'Стены'],
        'wc' => 'Санузел',
        'category' => 'Название категории',
        'gas' => 'Газ',
        'land_square' => 'Земля',
        'type_object_other' => 'Тип объекта'
    ],
    'kv61' => [
        'category' => 'Категория',
        'full_square' => 'Площадь общая',
        'living_square' => 'Площадь жилая',
        'kitchen_square' => 'Площадь кухни',
        'city' => 'Город',
        'district' => 'Район',
        'street' => 'Улица'
    ],
    'm2' => [

    ],
    'youla' => [
        'address' => 'Месторасположение',
        'owner_name' => 'Контактное лицо',
        'wall_material' => 'Материал дома'
    ],
    'move' => [
        'owner_name' => 'Пользователь',
        'rooms' => 'Количество комнат',
        'living_square' => 'Жилая комната',
        'balcony' => 'Тип балкона',
        'wc' => 'Тип санузла',
        'internet' => 'Интернет'
    ],
    'avito' => [
        'link' => 'ссылка на объявление',
        'price' => 'цена/зп',
        'address' => 'область, город',
        'description' => 'описание',
        'owner_name' => 'Контактное лицо',
        'category' => 'Категория',
        'kitchen_square' => 'Площадь кухни, м²',
        'wall_material' => 'Тип дома',
        'living_square' => 'Жилая площадь, м²',
        'condition_object' => 'Отделка',
        'deadline' => 'Название новостройки',
        'title' => 'Название',
        'district' => 'метро/район',
        'floor' => 'Этажей в доме',
        'land_square' => 'Площадь, сот.'
    ],
    'crystal' => [
        'rooms' => 'ком.',
        'floor' => 'Этаж/Этажность',
        'owner_phone' => ['Тел ', 'Тел']
    ],
    'without-realtor' => [
        'category' => 'Объект',
        'price' => 'Цена',
        'district' => 'Район',
        'street' => 'Ориентир',
        'rooms' => 'Ком-наты',
        'floor' => 'Этаж',
        'house_storey' => 'Этаж-ность',
        'wall_material' => 'Стены',
        'full_square' => 'Sоб',
        'living_square' => 'Sж',
        'kitchen_square' => 'Sк',
        'wc' => 'СУ',
        'balcony' => 'Бал',
        'condition_object' => 'Отд.хар',
        'address' => 'Адрес',
        'owner_name' => 'Имя',
        'land_square' => 'Sуч'
    ]
];