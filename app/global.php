<?php
/**
 * @file Global.php
 * @author Danil Sidorenko
 * @description Глобальный файл функций.
 */


use App\Core\Response;

if( !function_exists('dd') ) {
    /**
     * Функция для отладки кода.
     * Получает какие-то данные, возвращает результат
     * и останавливает код.
     *
     * @param mixed ...$data
     */
    function dd(...$data) {
        foreach ($data as $value) {
            print_r($value);
        }
        die();
    }
}

if( !function_exists('response') ) {
    /**
     * Создать ответ JSON ответ для API
     *
     * Имеет стрелочные функции:
     * ->setMessage($message) - установить сообщения
     * ->setData($data) - устновить данные
     * ->setCode($code) - установить HTTP-код
     * ->send() - отправить JSON ответ
     *
     * Возможно исключение:
     * MessageNotExistException - отсутвует сообщение в response()
     * SuccessNotExistException - отсутвует success в response()
     *
     * @return Response
     */
    function response() {
        return new Response();
    }
}

if( !function_exists('fields') ) {
    /**
     * Получить столбцы для сервисов
     *
     * @return array
     */
    function fields() {
        return include "fields.php";
    }
}