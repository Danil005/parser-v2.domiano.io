<?php

/**
 * @file MethodsInterface.php
 * @author Danil Sidorenko
 * @description Интерфейс методов
 *
 */

namespace App\Interfaces;

interface MethodsInterface
{
    /**
     * Метод для вызова метода API
     *
     * @return mixed
     */
    public function call();

//    /**
//     * Выполнить метод используя метод call()
//     *
//     * @return mixed
//     */
//    public function __invoke();
}