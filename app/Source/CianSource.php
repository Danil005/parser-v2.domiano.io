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

    /**
     * @throws Exception
     */
    public function call()
    {
        $this->return_data[] = $this->dictionary->sectionName();
        $this->return_data[] = $this->dictionary->title();
        $this->return_data[] = $this->dictionary->name();
        $this->return_data[] = $this->dictionary->phone();
        $this->return_data[] = $this->dictionary->price();
        $this->return_data[] = $this->dictionary->address();
        $this->return_data[] = $this->dictionary->description();
        $this->return_data[] = $this->dictionary->photos();
        $this->return_data[] = $this->dictionary->constructionYear();
        $this->return_data[] = $this->dictionary->floor();
    }

}