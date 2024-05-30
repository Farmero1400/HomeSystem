<?php

declare(strict_types=1);

namespace Farmero\homesystem\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;

use Farmero\homesystem\HomeSystem;
use Farmero\homesystem\api\HomeAPI;

class SetHome extends Command
{
    public function __construct()
    {
        $command = explode(":", HomeSystem::getConfigValue("sethome_cmd"));
        parent::__construct($command[0]);
        if (isset($command[1])) $this->setDescription($command[1]);
        $this->setAliases(HomeSystem::getConfigValue("sethome_aliases"));
        $this->setPermission("homesystem.cmd.sethome");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                if (!HomeAPI::existHome($sender, $args[0])) {
                    HomeAPI::setHome($sender, $sender, $args[0]);
                    $sender->sendMessage(HomeSystem::getConfigReplace("sethome_msg_good"));
                } else $sender->sendMessage(HomeSystem::getConfigReplace("sethome_msg_exist_home"));
            } else $sender->sendMessage(HomeSystem::getConfigReplace("sethome_msg_no_home"));
        }
    }
}