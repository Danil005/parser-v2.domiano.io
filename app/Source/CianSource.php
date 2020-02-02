<?php

/**
 * @file CianSource.php
 * @author Danil Sidorenko
 * @description Соурс для обработки Циана
 */

namespace App\Source;

use App\Interfaces\SourceInterface;
use App\Traits\SourceTrait;
use Exception;

class CianSource implements SourceInterface
{
    use SourceTrait;

    /**
     * Устновить source_id
     *
     * @return string
     */
    protected function sourceId()
    {
        return 'cian';
    }

    protected function fullSquare()
    {
        $title = $this->getValue('title');

        $square = str_replace(',', '.', explode(', ', $title)[1]);

        return floatval($square);
    }

    protected function livingSquare()
    {
        return false;
    }

    protected function kitchenSquare()
    {
        return false;
    }

    /**
     * @throws Exception
     */
    public function call()
    {

    }

}