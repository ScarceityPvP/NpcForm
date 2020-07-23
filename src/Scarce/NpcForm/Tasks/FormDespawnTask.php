<?php

namespace Scarce\NpcForm\Tasks;

use pocketmine\scheduler\Task;
use Scarce\NpcForm\Entity\BaseEntity;

class FormDespawnTask extends Task{


    public $entity;
    public function __construct(BaseEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Actions to execute when run
     *
     * @return void
     */
    public function onRun(int $currentTick)
    {
       $this->entity->flagForDespawn();
    }
}