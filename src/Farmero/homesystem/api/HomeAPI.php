<?php

declare(strict_types=1);

namespace Farmero\homesystem\api;

use pocketmine\world\Position;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

use Farmero\homesystem\HomeSystem;

class HomeAPI
{
    public static Config $data;

    public function __construct()
    {
        self::$data = new Config(HomeSystem::getInstance()->getDataFolder() . "HomeData.json", Config::JSON);
    }

    public static function createPlayer($player): void
    {
        if (!self::existPlayer($player)) {
            self::$data->set(HomeSystem::getPlayerName($player), []);
            self::$data->save();
        }
    }

    public static function existPlayer($player): bool
    {
        return self::$data->exists(HomeSystem::getPlayerName($player));
    }

    public static function getAllHomes($player): array
    {
        $homes = [];
        foreach (self::$data->get(HomeSystem::getPlayerName($player)) as $home => $pos) {
            $homes[] = $home;
        }
        return $homes;
    }

    public static function existHome($player, string $name): bool
    {
        return in_array($name, self::getAllHomes($player));
    }

    public static function setHome(Player $pos, $player, string $name): void
    {
        self::$data->setNested(HomeSystem::getPlayerName($player) . ".$name", [$pos->getPosition()->getX(), $pos->getPosition()->getY(), $pos->getPosition()->getZ(), $pos->getWorld()->getId()]);
        self::$data->save();
    }

    public static function getHome($player, string $name): Position
    {
        $pos = self::$data->get(HomeSystem::getPlayerName($player))[$name];
        return new Position($pos[0], $pos[1], $pos[2], Server::getInstance()->getWorldManager()->getWorld($pos[3]));
    }

    public static function delHome($player, string $name): void
    {
        self::$data->removeNested(HomeSystem::getPlayerName($player) . ".$name");
        self::$data->save();
    }
}