<?php

namespace Scarce\NpcForm\Entity\Entities;

use Scarce\NpcForm\Entity\BaseEntity;

class Zombie_Pigman extends BaseEntity{

    public const IDENTIFIER = "NpcForm:Zombie_Pigman";

    public const NETWORK_ID = self::ZOMBIE_PIGMAN;

    public $height = 1.95;
    public $width = .6;
    public $eyeHeight = 1.6;


    public function getName()
    {
        return "Zombie_Pigman";
    }
}
