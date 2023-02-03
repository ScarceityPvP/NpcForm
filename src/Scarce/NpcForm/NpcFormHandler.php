<?php


namespace Scarce\NpcForm;

use InvalidArgumentException;
use Scarce\NpcForm\Entities\Npc;
use pocketmine\Server;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\Plugin;
use pocketmine\world\World;

final class NpcFormHandler
{

    public static bool $registered = false;

    public static function isRegistered() : bool
    {
        return self::$registered;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function register(Plugin $plugin) : void
    {
        EntityFactory::getInstance()->register(Npc::class, function (World $world, CompoundTag $nbt): Npc {
            return new Npc(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['Npc', 'minecraft:npc'], null);

        if (self::$registered) {
            throw new \InvalidArgumentException($plugin->getName() . " tried to register " . self::class . " twice!");
        }
        self::$registered = true;
        Server::getInstance()->getPluginManager()->registerEvents(new NpcFormEventHandler(), $plugin);
    }
}
