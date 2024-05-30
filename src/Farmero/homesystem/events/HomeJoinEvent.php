<?php

declare(strict_types=1);

namespace Farmero\homesystem\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use Farmero\homesystem\api\HomeAPI;

class HomeJoinEvent implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        HomeAPI::createPlayer($event->getPlayer());
    }
}