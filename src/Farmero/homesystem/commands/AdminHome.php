<?php

declare(strict_types=1);

namespace Farmero\homesystem\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

use Farmero\homesystem\api\HomeAPI;
use Farmero\homesystem\HomeSystem;

class AdminHome extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystem::getConfigValue("adminhome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystem::getConfigValue("adminhome_aliases"));
        $this->setPermission("homesystem.cmd.home");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("homesystem.cmd.home")) {
                if (isset($args[0])) {
                    if (HomeAPI::existPlayer($args[0])) {
                        if ((isset($args[1])) and (HomeAPI::existHome($args[0], $args[1]))) {
                            $sender->teleport(HomeAPI::getHome($args[0], $args[1]));
                            $sender->sendMessage(HomeSystem::getConfigReplace("adminhome_msg_teleportation"));
                        } else {
                            $homes = implode(", ", HomeAPI::getAllHomes($args[0]));
                            $sender->sendMessage(HomeSystem::getConfigReplace("adminhome_msg_list", ["{home}", "{player}"], [$homes, $args[0]]));
                        }
                    } else $sender->sendMessage(HomeSystem::getConfigReplace("adminhome_no_exist_player"));
                } else $sender->sendMessage(HomeSystem::getConfigReplace("adminhome_msg_no_player"));
            } else $sender->sendMessage(HomeSystem::getConfigReplace("no_perm"));
        }
    }
}