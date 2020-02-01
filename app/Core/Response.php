<?php
/**
 * @file Response.php
 * @author Danil Sidorenko
 * @description Класс для работы с ответами на сервере
 */

namespace App\Core;

use App\Exceptions\MessageNotExistException;
use App\Interfaces\ResponseInterface;
use App\Exceptions\SuccessNotExistException;

class Response implements ResponseInterface
{
    /**
     * Успешный ли запрос или нет
     *
     * @var bool
     */
    private $success;

    /**
     * Сообщение ответа
     *
     * @var string
     */
    private $message;

    /**
     * Данные ответа
     *
     * @var array
     */
    private $data = [];

    /**
     * HTTP-code ответа
     *
     * @var int
     */
    private $code;

    /**
     * Отправить успешный ответ
     *
     * @return Response
     */
    public function success()
    {
        $this->success = true;

        return $this;
    }

    /**
     * Отправить неудачный ответ
     *
     * @return Response
     */
    public function error()
    {
        $this->success = false;

        return $this;
    }

    /**
     * Установить сообщения
     *
     * @param $message
     * @return Response
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Установить код ответа
     *
     * @param $code
     * @return Response
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Установить данные
     *
     * @param $data
     * @return Response
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Отправить ответ
     *
     * @return mixed
     * @throws MessageNotExistException
     */
    public function send()
    {
        if(empty($this->message))
            throw new MessageNotExistException('Message must be not empty.');

        return json_encode([
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
        ]);
    }
}