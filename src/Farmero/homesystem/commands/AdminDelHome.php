<?php

declare(strict_types=1);

namespace Farmero\homesystem\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

use Farmero\homesystem\api\HomeAPI;
use Farmero\homesystem\HomeSystem;

class AdminDelHome extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystem::getConfigValue("admindelhome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystem::getConfigValue("admindelhome_aliases"));
        $this->setPermission("homesystem.cmd.admindelhome");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("homesystem.cmd.admindelhome")) {
                if (isset($args[0])) {
                    if (HomeAPI::existPlayer($args[0])) {
                        if (isset($args[1])) {
                            if (HomeAPI::existHome($args[0], $args[1])) {
                                HomeAPI::delHome($args[0], $args[1]);
                                $sender->sendMessage(HomeSystem::getConfigReplace("admindelhome_msg_good"));
                            } else $sender->sendMessage(HomeSystem::getConfigReplace("admindelhome_no_exist_home"));
                        } else $sender->sendMessage(HomeSystem::getConfigReplace("admindelhome_no_home"));
                    } else $sender->sendMessage(HomeSystem::getConfigReplace("admindelhome_no_exist_player"));
                } else $sender->sendMessage(HomeSystem::getConfigReplace("admindelhome_msg_no_player"));
            } else $sender->sendMessage(HomeSystem::getConfigReplace("no_perm"));
        }
    }
}