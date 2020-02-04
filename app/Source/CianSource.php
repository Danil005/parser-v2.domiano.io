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
        $living_square = $this->getValue('living_square');

        return floatval(str_replace(',', '.', $living_square));
    }

    protected function kitchenSquare()
    {
        $kitchen_square = $this->getValue('kitchen_square');

        return floatval(str_replace(',', '.', $kitchen_square));
    }

    /**
     * @throws Exception
     */
    public function call()
    {
        $this->return_data[] = $this->getValue('deadline') ? ['property_type'=>'new_building'] : "";
    }

}