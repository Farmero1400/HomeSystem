<?php

declare(strict_types=1);

namespace Farmero\homesystem\commands;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

use Farmero\homesystem\HomeSystem;
use Farmero\homesystem\api\HomeAPI;
use Farmero\homesystem\task\TeleportationTask;

class Home extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystem::getConfigValue("home_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystem::getConfigValue("home_aliases"));
        $this->setPermission("homesystem.cmd.home");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ((isset($args[0])) and (HomeAPI::existHome($sender, $args[0]))) {
                if ($sender->hasPermission("homesystem.cmd.home")) {
                    $sender->teleport(HomeAPI::getHome($sender, $args[0]));
                    $sender->sendMessage(HomeSystem::getConfigReplace("home_msg_teleport"));
                } else {
                    $sender->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * (HomeSystem::getConfigValue("home_time") + 2), 10));
                    new TeleportationTask($sender, HomeAPI::getHome($sender, $args[0]), HomeSystem::getConfigReplace("home_msg_teleport"));
                }
            } else {
                $homes = implode(", ", HomeAPI::getAllHomes($sender));
                $sender->sendMessage(HomeSystem::getConfigReplace("home_msg_list", ["{home}"], [$homes]));
            }
        }
    }
}