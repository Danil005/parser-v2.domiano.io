<?php

/**
 * @file SourceInterface.php
 * @author Danil Sidorenko
 * @description Интерфейс source для работы с платформами
 *
 */

namespace App\Interfaces;

interface SourceInterface
{
    public function __construct($data);

    public function call();
}