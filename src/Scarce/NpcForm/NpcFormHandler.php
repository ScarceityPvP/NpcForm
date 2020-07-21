<?php


namespace Scarce\NpcForm;

use http\Exception\InvalidArgumentException;
use pocketmine\plugin\Plugin;

class NpcFormHandler{

    public static $registered = false;

    public function isRegistered(){
        if (self::$registered){
            return true;
        }else{
            return false;
        }
    }

    public static function register(Plugin $plugin){
        if (self::$registered){
            throw new InvalidArgumentException($plugin->getName() . " tried to register " . self::class . " twice!");
        }else{
            self::$registered = true;
            $plugin->getServer()->getPluginManager()->registerEvents(new NpcFormEventHandler(), $plugin);
        }
    }

}
