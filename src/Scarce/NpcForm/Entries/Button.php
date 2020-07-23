<?php

namespace Scarce\NpcForm\Entries;

use JsonSerializable;

class Button implements JsonSerializable
{

    public $data;

    public function __construct(string $name, ?array $data = null, int $mode = 0, string $text = "", int $type = 1)
    {
        $this->data = [
            "button_name" => $name,
            "data" => $data,
            "mode" => $mode,
            "text" => $text,
            "type" => $type,
        ];
    }


    public function jsonSerialize()
    {
        return $this->data;
    }
}
