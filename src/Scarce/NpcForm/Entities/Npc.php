<?php


namespace Scarce\NpcForm\Entities;


use Scarce\NpcForm\NpcForm;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\player\Player;
use pocketmine\world\Position;

class Npc extends Entity {

    protected function getInitialSizeInfo() : EntitySizeInfo {
        return new EntitySizeInfo(1.95, .6, 1.6);
    }

    public static function getNetworkTypeId() : string {
        return EntityIds::NPC;
    }

    public ?NpcForm $form = null;


    public function canSaveWithChunk() : bool{
        return false;
    }

    public function initEntity(CompoundTag $tag): void
    {
        parent::initEntity($tag);
        $this->getNetworkProperties()->setByte(EntityMetadataProperties::HAS_NPC_COMPONENT, (int)true);
    }

    public function setTitle(string $title) : void {
        $this->setNameTag($title);
    }

    public function getTitle() : string {
        return $this->getNameTag();
    }

    public function setContent(string $text): void {
        $this->getNetworkProperties()->setString(EntityMetadataProperties::INTERACTIVE_TAG, $text);
    }

    //Current Action Of This Is Unknown
    public function setSkinIndex(string $index): void {
        $this->getNetworkProperties()->setString(EntityMetadataProperties::NPC_SKIN_INDEX, $index);
    }

    public function setForm(NpcForm $form):void {
        $this->form = $form;
    }

    /**
     * @param ?(array{"button_name": string, "data": ?(mixed[]), "mode": int, "text": string, "type": int}[]) $data
     * @see Npc::buttons
     * @throws \JsonException
     */
    public function setActions(?array $data): void {
        $this->getNetworkProperties()->setString(EntityMetadataProperties::NPC_ACTIONS, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    }


    public function getName(): string {
        return "NPC";
    }

    /**
     * NOTICE: Does not spawn the entity.
     * @see $this->spawnTo()
     * @see $this->spawnToAll()
     */
    public static function create(Position $position, int $yaw, int $pitch):self{
        $entity = new self(Location::fromObject($position, $position->getWorld(), $yaw, $pitch));
        return $entity;
    }

    public function handleResponse(Player $player, ?int $data):void {
        if ($this->form !== null){
            if ($this->form instanceof NpcForm){
                $this->form->handleResponse($player, $data);
            }
        }
    }

}
