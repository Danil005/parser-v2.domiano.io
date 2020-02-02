<?php

/**
 * @file Templates.php
 * @author Danil Sidorenko
 * @description Шаблоны для создания файлов
 */

namespace Domian\Console\Templates;

class Templates
{
    public function source($argv, ...$data)
    {
        return "<?php \n\n" .
            "/**\n" .
            " * @file " . $argv[2] . ".php\n" .
            " * @author Danil Sidorenko\n" .
            " * @description Source для обработки сервиса\n" .
            " */\n\n" .
            "namespace App\Source;\n\n" .
            "use App\Interfaces\SourceInterface; \n" .
            "use App\Traits\SourceTrait; \n" .
            "use Exception; \n\n" .
            "class " . $argv[2] . " implements SourceInterface\n{\n" .
            "    use SourceTrait; \n\n" .
            "    /**\n" .
            "     * Устновить source_id\n" .
            "     * \n" .
            "     * @return string\n" .
            "     */\n" .
            "    protected function sourceId()\n" .
            "    {\n" .
            "        return '".$data[0]."';\n" .
            "    }\n\n" .
            "    /**\n" .
            "     * Спарсить полную площадь\n" .
            "     * Если оставить false, то парсить не будет.\n" .
            "     * Если убрать return false, то нужно будет реализовать метод и\n" .
            "     * вернуть значение.\n" .
            "     * \n" .
            "     * @return mixed\n" .
            "     */\n" .
            "    protected function fullSquare()\n" .
            "    {\n" .
            "        return false;\n" .
            "    }\n\n" .
            "    /**\n" .
            "     * Спарсить жилую площадь\n" .
            "     * Если оставить false, то парсить не будет.\n" .
            "     * Если убрать return false, то нужно будет реализовать метод и\n" .
            "     * вернуть значение.\n" .
            "     * \n" .
            "     * @return mixed\n" .
            "     */\n" .
            "    protected function livingSquare()\n" .
            "    {\n" .
            "        return false;\n" .
            "    }\n\n" .
            "    /**\n" .
            "     * Спарсить площадь кухни\n" .
            "     * Если оставить false, то парсить не будет.\n" .
            "     * Если убрать return false, то нужно будет реализовать метод и\n" .
            "     * вернуть значение.\n" .
            "     * \n" .
            "     * @return mixed\n" .
            "     */\n" .
            "    protected function kitchenSquare()\n" .
            "    {\n" .
            "        return false;\n" .
            "    }\n\n" .
            "    /**\n" .
            "     * Основная функция, которая выполняется, когда запускается\n" .
            "     * парсер. Здесь находится логика парсера.\n" .
            "     * \n" .
            "     * @return mixed\n" .
            "     * @throws Exception\n" .
            "     */\n" .
            "    public function call()\n" .
            "    {\n" .
            "        \n" .
            "    }\n\n" .
            "}";
    }
}