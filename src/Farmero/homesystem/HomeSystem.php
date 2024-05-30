<?php

declare(strict_types=1);

namespace Farmero\homesystem;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use Farmero\homesystem\commands\{AdminDelHome, AdminHome, AdminSetHome, DelHome, Home, SetHome};
use Farmero\homesystem\api\HomeAPI;
use Farmero\homesystem\events\HomeJoinEvent;

class HomeSystem extends PluginBase
{
    private static HomeSystem $homeSystem;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();

        self::$homeSystem = $this;

        new HomeAPI();

        $this->getServer()->getCommandMap()->registerAll("HomeSystem", [
            new AdminDelHome(),
            new AdminHome(),
            new AdminSetHome(),
            new DelHome(),
            new Home(),
            new SetHome()
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new HomeJoinEvent(), $this);
    }

    public static function getInstance(): HomeSystem
    {
        return self::$homeSystem;
    }

    public static function getConfigReplace(string $path, array $replace = [], array $replacer = []): string
    {
        $return = str_replace("{prefix}", self::$homeSystem->getConfig()->get("prefix"), self::$homeSystem->getConfig()->get($path));
        return str_replace($replace, $replacer, $return);
    }

    public static function getPlayerName($player): string
    {
        if ($player instanceof Player) return $player->getName(); else return $player;
    }

    public static function getConfigValue(string $path): mixed
    {
        return self::$homeSystem->getConfig()->get($path);
    }
}