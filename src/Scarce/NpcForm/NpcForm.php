<?php


namespace Scarce\NpcForm;

use Scarce\NpcForm\Entities\Npc;
use pocketmine\player\Player;
use pocketmine\world\Position;

class NpcForm{

    public string $content = "";
         
    /**
     * @var array{"button_name": string, "data": ?(mixed[]), "mode": int, "text": string, "type": int}[]
     */
    public array $buttons = [];

    public ?\Closure $callable = null;

    private Npc $entity;

    public function __construct(?callable $callable, Position $position, int $yaw = 90, int $pitch =  0)
    {
        $this->setCallable($callable);
        $this->entity = Npc::create($position, $yaw, $pitch);
    }

    //Sets The title of the form and the NameTag of the entity
    public function setTitle(string $title): void{
        $this->entity->setTitle($title);
    }

    //The action of this method is currently unknown
    public function setSkinIndex(string $id): void{
        $this->entity->setSkinIndex($id);
    }

    //Returns the title of the form
    public function getTitle():string {
        return $this->entity->getTitle();
    }

    //Sets the text of the form
    public function setContent(string $content): void {
        $this->entity->setContent($this->content = $content);
    }

    //Returns the text of the form
    public function getContent(): string {
        return $this->content;
    }

    /**
     *  Adds a button to the form[some ascpects are currently unknown.
     * @throws \JsonException
     */
    public function addButton(string $name):void{
        $this->buttons[] = [
            "button_name" => $name,
            "data" => null,
            "mode" => 0,
            "text" => "",
            "type" => 1,
        ];
        $this->entity->setActions($this->buttons);
    }

    //Sets the callable of the form
    public function setCallable(?callable $callable):void{
        if ($callable === null) $this->callable = null;
        else $this->callable = $callable instanceof \Closure ? $callable : \Closure::fromCallable($callable);
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

    /**
     * Creates a json storable version of the forum data.
     * @return array{"title": string, "content": string, "buttons": array{"button_name": string, "data": ?(mixed[]), "mode": int, "text": string, "type": int}[]}
     * @see $this->buttons
     */
    public function jsonSerialize() : array {
        return [
            "title" => $this->getTitle(),
            "content" => $this->getContent(),
            "buttons" => $this->buttons
        ];
    }

    //Handles the response received from the form
    public function handleResponse(Player $player, ?int $data): void {
        if ($this->callable !== null){
            ($this->callable)($player, $data);
        }
    }

}
