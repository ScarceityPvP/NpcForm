<?php


namespace Scarce\NpcForm;

use pocketmine\level\Position;
use pocketmine\Player;
use Scarce\NpcForm\Entities\Npc;

class NpcForm{


    public $data;

    public $callable = null;

    private $entity;

    public function __construct(?callable $callable, Position $position, int $yaw = 90, int $pitch =  0)
    {
        $this->setCallable($callable);
        $this->data["title"] = "";
        $this->data["content"] = "";
        $this->data["buttons"] = [];
        $this->entity = Npc::create($position, $yaw, $pitch);
    }

    //Sets The title of the form and the NameTag of the entity
    public function setTitle(string $title): void{
        $this->data["title"] = $title;
        $this->entity->setTitle($title);
    }

    //The action of this method is currently unknown
    public function setSkinIndex(string $id){
        $this->entity->setSkinIndex($id);
    }

    //Returns the title of the form
    public function getTitle():string {
        return  $this->data["title"];
    }

    //Sets the text of the form
    public function setContent(string $content): void {
        $this->data["content"] = $content;
        $this->entity->setContent($this->data["content"]);
    }

    //Returns the text of the form
    public function getContent(): string {
        return $this->data["content"];
    }

    //Adds a button to the form[some ascpects are currently unknown]
    public function addButton(string $name):void{
        $this->data["buttons"][] = [
            "button_name" => $name,
            "data" => null,
            "mode" => 0,
            "text" => "",
            "type" => 1,
        ];
        $this->entity->setActions($this->data["buttons"]);
    }

    //Sets the callable of the form
    public function setCallable(?callable $callable):void{
        $this->callable = $callable;
    }

    //Returns the callable of the form
    public function getCallable():?callable {
        return $this->callable;
    }

    //Returns the Npc Entity
    public function getEntity():Npc{
        return $this->entity;
    }

    //Spawns the Entity to a specific Player
    public function spawnTo(Player $player): void
    {
        $this->entity->setForm($this);
        $this->entity->spawnTo($player);
    }

    //Spawns the Entity to all Players
    public function spawnToAll() : void {
        $this->entity->setForm($this);
        $this->entity->spawnToAll();
    }

    //creates a json storable version of the forum data
    public function jsonSerialize(){
        return $this->data;
    }

    //Handles the response received from the form
    public function handleResponse(Player $player, ?int $data): void {
        if ($this->callable !== null){
            ($this->callable)($player, $data);
        }
    }

}
