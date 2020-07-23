<?php


namespace Scarce\NpcForm;

use InvalidArgumentException;
use pocketmine\entity\Entity;
use pocketmine\plugin\Plugin;
use Scarce\NpcForm\Entity\BaseEntity;
use Scarce\NpcForm\Entity\Entities\Zombie_Pigman;

final class NpcFormHandler{

    public static $registerent = null;
    public static $registerd_entities = [];

    public static function isRegistered(){
        if (self::$registerent !== null){
            return true;
        }else{
            return false;
        }
    }

    public static function getRegisterant(): ?Plugin{
        if (self::isRegistered()){
            return self::$registerent;
        }
        return null;
    }

    public static function register(Plugin $plugin){
        Entity::registerEntity(Zombie_Pigman::class, true);
        if (self::$registerent !== null){
            throw new InvalidArgumentException($plugin->getName() . " tried to register " . self::class . " twice!");
        }
        self::$registerent = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents(new NpcFormEventHandler(), $plugin);

    }

    //
    public static function getFormEntity(string $identifier){
        return self::$registerd_entities[$identifier] ?? null;
    }

    //Should be used to reigister custom Form Entities that extend Base Entity
    public static function registerFormEntity(string $class, bool $override){
        if (self::isRegistered()){
            $plugin = self::$registerent;
            if ($plugin instanceof Plugin){
                if (is_a($class, BaseEntity::class, true)){
                    if (isset(self::$registerd_entities[$class::IDENTIFIER]) && $override === true){
                        $identifer = $class::IDENTIFIER;
                        throw new \http\Exception\InvalidArgumentException("$identifer has already been registered");
                    }
                    self::$registerd_entities[$class::IDENTIFIER] = $class;
                }else{
                    $baseentity = BaseEntity::class;
                    throw new InvalidArgumentException("$class is not a child class of $baseentity");
                }

            }
        }
    }



}
