<?php

declare(strict_types=1);

namespace Farmero\homesystem\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

use Farmero\homesystem\HomeSystem;
use Farmero\homesystem\api\HomeAPI;

class AdminSetHome extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystem::getConfigValue("adminsethome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystem::getConfigValue("adminsethome_aliases"));
        $this->setPermission("homesystem.cmd.adminsethome");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("homesystem.cmd.adminsethome")) {
                if (isset($args[0])) {
                    if (HomeAPI::existPlayer($args[0])) {
                        if (isset($args[1])) {
                            if (!HomeAPI::existHome($args[0], $args[1])) {
                                HomeAPI::setHome($sender, $args[0], $args[1]);
                                $sender->sendMessage(HomeSystem::getConfigReplace("adminsethome_msg_good"));
                            } else $sender->sendMessage(HomeSystem::getConfigReplace("adminsethome_exist_home"));
                        } else $sender->sendMessage(HomeSystem::getConfigReplace("adminsethome_no_home"));
                    } else $sender->sendMessage(HomeSystem::getConfigReplace("adminsethome_no_exist_player"));
                } else $sender->sendMessage(HomeSystem::getConfigReplace("adminsethome_msg_no_player"));
            } else $sender->sendMessage(HomeSystem::getConfigReplace("no_perm"));
        }
    }
}