<?php

/**
 * @file ResponseInterface.php
 * @author Danil Sidorenko
 * @description Интерфейс для Response class
 */

namespace App\Interfaces;

use App\Core\Response;

interface ResponseInterface
{

    /**
     * Отправить успешный ответ
     *
     * @return Response
     */
    public function success();

    /**
     * Отправить неудачный ответ
     *
     * @return Response
     */
    public function error();

    /**
     * Установить сообщения
     *
     * @param $message
     * @return Response
     */
    public function setMessage($message);

    /**
     * Установить код ответа
     *
     * @param $code
     * @return Response
     */
    public function setCode($code);

    /**
     * Установить данные
     *
     * @param $data
     * @return Response
     */
    public function setData($data);

    /**
     * Отправить ответ
     *
     * @return mixed
     */
    public function send();
}