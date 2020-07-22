<?php


namespace Scarce\NpcForm;

use InvalidArgumentException;
use pocketmine\entity\Entity;
use pocketmine\plugin\Plugin;
use Scarce\NpcForm\Entities\Npc;

final class NpcFormHandler{

    public static $registered = false;

    public static function isRegistered(){
        if (self::$registered){
            return true;
        }else{
            return false;
        }
    }

    public static function register(Plugin $plugin){
        Entity::registerEntity(Npc::class, true);
        if (self::$registered){
            throw new InvalidArgumentException($plugin->getName() . " tried to register " . self::class . " twice!");
        }
        self::$registered = true;
        $plugin->getServer()->getPluginManager()->registerEvents(new NpcFormEventHandler(), $plugin);

    }

}
