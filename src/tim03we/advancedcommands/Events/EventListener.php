<?php

namespace tim03we\advancedcommands\Events;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\entity\Entity;

class EventListener implements Listener {

    public $vanish = array();

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
        $player->setNameTagVisible(true);
    }
}
