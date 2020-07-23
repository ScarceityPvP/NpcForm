<?php


namespace Scarce\NpcForm;

use InvalidArgumentException;
use pocketmine\level\Position;
use pocketmine\Player;
use Scarce\NpcForm\Entity\BaseEntity;
use Scarce\NpcForm\Entries\Button;
use JsonSerializable;
class NpcForm implements FormIds, JsonSerializable{


    public $data;
    public $callable = null;

    public $form_listener = null;

    private $entity;

    public $buttons;

    public function __construct(string $identifier, ?callable $callable, Position $position, bool $cantakedamage = false, int $yaw = 90, int $pitch =  0)
    {
        $this->setCallable($callable);
        $this->data["title"] = "";
        $this->data["content"] = "";
        $this->data["buttons"] = [];

        $entityclass = NpcFormHandler::getFormEntity($identifier);
        if ($entityclass === null){
            throw new InvalidArgumentException("Tried to create a NpcForm with a non-registered entity-identifier");
        }
        $this->entity = $entityclass::create($position, $yaw, $pitch);
        $this->entity->setCanTakeDamage($cantakedamage);

    }

    //Sets The title of the form and the NameTag of the entity
    public function setTitle(string $title): void{
        $this->data["title"] = $title;
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
    }

    //Returns the text of the form
    public function getContent(): string {
        return $this->data["content"];
    }

    //Adds a button to the form[some ascpects are currently unknown]
    public function addButton(Button $button, callable $callable = null):void{
         $this->data["buttons"] = $button->data;
         $this->buttons[] = $button;
         if ($callable !== null){
             $this->form_listener[array_key_last($this->buttons)] = $callable;
         }
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
    public function getEntity():BaseEntity{
        return $this->entity;
    }

    //Spawns the Entity to a specific Player
    public function spawnTo(Player $player): void
    {
        if ($this->entity instanceof BaseEntity){
            $this->entity->setForm($this);
            $this->entity->spawnTo($player);
        }
    }

    //Spawns the Entity to all Players
    public function spawnToAll() : void {
        if ($this->entity instanceof BaseEntity){
            $this->entity->setForm($this);
            $this->entity->spawnToAll();
        }
    }

    //creates a json storable version of the forum data
    public function jsonSerialize(){
        return $this->data["buttons"];
    }

    //Scheduales the entity to despawn after a specific amount of time
    public function despawnAfter(int $time){

    }

    //Handles the response received from the form
    public function handleResponse(Player $player, ?int $data): void {
        if ($this->callable !== null){
            if(isset($this->form_listener[$data])){
                ($this->form_listener[$data])($player, $this->buttons[$data], $data);
            }else{
                ($this->callable)($player, $data);
            }
        }
    }

    public function onClose(Player $player){
    }

}
