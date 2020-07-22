<?php

namespace App;

use App\Exceptions\ActionNotFoundException;
use App\Exceptions\BodyInvalidException;
use App\Exceptions\MessageNotExistException;
use Exception;

class Api
{

    /**
     * Версия API
     *
     * @var string
     */
    public $version = "v2";

    /**
     * Метод запроса
     *
     * @var string
     */
    protected $method = '';

    /**
     * Массив частей ссылки (деление по символу /)
     *
     * @var array
     */
    public $request_uri = [];

    /**
     * Данные которые пришли с Quarry
     *
     * @var array
     */
    public $request_params = [];

    /**
     * Данные которые приходят с body
     *
     * @var array
     */
    public $request_body = [];

    /**
     * Методы API
     *
     * @var array
     */
    protected $actions = [
        'parse'
    ];

    /**
     * Текущий метод, который выполняется
     *
     * @var string
     */
    protected $action;

    /**
     * Api constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: content-type");
        header("Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE");
        header("Content-Type: application/json");


        //Массив GET параметров разделенных слешем
        $this->request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->request_params = $_REQUEST;

        $this->request_body = json_decode(@file_get_contents("php://input"), true);

        //Определение метода запроса
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
    }

    /**
     * Получить текущий метод
     *
     * @return string
     * @throws ActionNotFoundException
     */
    private function getAction()
    {
        $action = ($this->request_uri[1]);


        if (in_array($action, $this->actions)) {
            return $action;
        }

        throw new ActionNotFoundException('Method not found. Available methods in data.');
    }

    /**
     * Отправить ответ с ошибкой о неверном body
     * $error - переменная, где указывается причина.
     *
     * @param $error
     * @return mixed
     * @throws MessageNotExistException
     */
    private function invalidBody($error)
    {
        return response()->error()->setMessage('Invalid Body')->setData($error)->send();
    }

    /**
     * Проверка body.
     * Обязательные поля: deal_type, source, data
     *
     * @return mixed
     * @throws MessageNotExistException
     */
    private function verificationBody()
    {
        if( is_array($this->request_body) ) {
            $body = array_keys($this->request_body);
            foreach (['deal_type', 'source', 'data'] as $item)
                if (!in_array($item, $body))
                    return $this->invalidBody([$item => "Not Exist in Body"]);

        } else {
            foreach ($this->request_body as $item) {
                $body = array_keys($item);
                foreach (['deal_type', 'source', 'data'] as $value)
                    if (!in_array($value, $body))
                        return $this->invalidBody([$value => "Not Exist in Body"]);
            }
        }
        return true;
    }

    /**
     * Запустить API
     *
     * @return mixed
     * @throws MessageNotExistException
     */
    public function run()
    {
        // Ссылка должна быть /v2/api
        if (array_shift($this->request_uri) !== $this->version && array_shift($this->request_uri) !== 'api' ) {
            return response()->error()->setMessage('Invalid URI')->send();
        }


        try {
            $verify = $this->verificationBody();
            if( $verify !== true )
                return $verify;

            $this->action = $this->getAction();
            $action = ucfirst($this->action) . 'Method';
            $class = '\\App\\Methods\\' . $action;


            return (new $class())->call();
        } catch (ActionNotFoundException $e) {
            return response()->error()->setMessage($e->getMessage())->setData($this->actions)->send();
        }
    }

    /**
     * Получить тип сделки
     *
     * @return string
     */
    protected function getDealType()
    {
        return $this->request_body['deal_type'];
    }

    /**
     * Получить тип source сервиса
     *
     * @param array $data
     * @return mixed
     */
    protected function getSource($data = [])
    {
        return empty($data) ? $this->request_body['source'] : $data['source'];
    }

    /**
     * Получить данные с Excel
     *
     * @param array $data
     * @return array
     */
    protected function getData($data = [])
    {
        return empty($data) ? $this->request_body['data'] : $data['data'];
    }
}