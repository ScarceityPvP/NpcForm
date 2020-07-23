<?php


namespace Scarce\NpcForm\Entity;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use Scarce\NpcForm\NpcForm;
use Scarce\NpcForm\NpcFormHandler;

abstract class BaseEntity extends Entity{

    public const IDENTIFIER = "";

    public $form = null;
    public $cantakedamage = false;


    public function __construct(Level $level, CompoundTag $nbt)
    {
        $this->setCanSaveWithChunk(true);
        parent::__construct($level, $nbt);
        NpcFormHandler::registerFormEntity(static::class, false);
    }


    //Used to define this specific entity as an entity which holds a form
    public function initEntity(): void
    {
        parent::initEntity();
        $this->propertyManager->setByte(self::DATA_HAS_NPC_COMPONENT, true);
    }

    //Sets the Title of the Form
    public function setTitle(string $title){
        $this->setNameTag($title);
    }
    //Sets the Content of the Form
    public function setContent(string $text): void {
        $this->propertyManager->setString(self::DATA_INTERACTIVE_TAG, $text);
    }

    //Current Action Of This Is Unknown
    public function setSkinIndex(string $index): void {
        $this->propertyManager->setString(self::DATA_NPC_SKIN_INDEX, $index);
    }

    //Sets the Buttons of the Form
    public function setActions(?array $data): void {
        $this->propertyManager->setString(self::DATA_NPC_ACTIONS, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    //Sets the corresponding Form Class
    public function setForm(NpcForm $form):void {
        $this->setTitle($form->getTitle());
        $this->setContent($form->getContent());
        $this->setActions($form->data["buttons"]);
        $this->form = $form;
    }


    //Used to create the Entity
    public static function create(Position $position, int $yaw, int $pitch):Entity{
        $nbt = self::createBaseNBT($position, null, $yaw, $pitch);
        $entity = new static($position->getLevel(), $nbt);
        return $entity;
    }

    public function getIdentifier():string {
        return self::IDENTIFIER;
    }

    public function canTakeDamage(): bool {
        return $this->cantakedamage;
    }

    public function setCanTakeDamage(bool $value): void {
        $this->cantakedamage = $value;
    }

    //Handles Response from button interactions
    public function handleResponse(Player $player, ?int $data):void {
        if ($this->form !== null){
            if ($this->form instanceof NpcForm){
                $this->form->handleResponse($player, $data);
            }
        }
    }

    //Function Called when the form is closed
    public function onClose(Player $player): void{
        if ($this->form !== null){
            if ($this->form instanceof NpcForm){
                $this->form->onClose($player);
            }
        }
    }




}
