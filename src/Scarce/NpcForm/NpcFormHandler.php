<?php


namespace Scarce\NpcForm;

use InvalidArgumentException;
use pocketmine\plugin\Plugin;

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
        if (self::$registered){
            throw new InvalidArgumentException($plugin->getName() . " tried to register " . self::class . " twice!");
        }
        self::$registered = true;
        $plugin->getServer()->getPluginManager()->registerEvents(new NpcFormEventHandler(), $plugin);

    }

}
