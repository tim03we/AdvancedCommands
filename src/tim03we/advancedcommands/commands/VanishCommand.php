<?php

declare(strict_types=1);

namespace tim03we\advancedcommands\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\entity\Entity;
use tim03we\advancedcommands\Main;

class VanishCommand extends Command {

    public $vanish = array();
	
	public function __construct(Main $plugin) {
		parent::__construct("vanish", "AdvancedCommands", "/vanish", ["v"]);
		$this->setPermission("advanced.vanish.use");
		$this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(!$sender instanceof Player) {
			$sender->sendMessage("Run this Command InGame!");
			return true;
        }
        $settings = new Config($this->plugin->getDataFolder() . "messages.yml", Config::YAML);
        if(empty($args[0])){
            if(!in_array($sender->getName(), $this->vanish)){
                $this->vanish[] = $sender->getName();
                $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
                $sender->setNameTagVisible(false);
                $sender->sendMessage($settings->get("VanishEnabled-Message"));
            }elseif(in_array($sender->getName(), $this->vanish)){
                unset($this->vanish[array_search($sender->getName(), $this->vanish)]);
                $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
                $sender->setNameTagVisible(true);
                $sender->sendMessage($settings->get("VanishDisabled-Message"));
            }
            return false;
        }
        
        if($this->plugin->getServer()->getPlayer($args[0])){
            $player = $this->plugin->getServer()->getPlayer($args[0]);
            $change = $player->getName();
            if(!in_array($player->getName(), $this->vanish)){
                $this->vanish[] = $player->getName();
                $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
                $player->setNameTagVisible(false);
                $player->sendMessage($settings->get("VanishEnabled-Message"));
                $sender->sendMessage($this->convert($settings->get("VanishEnabledOther-Message"), $change));
            }elseif(in_array($player->getName(), $this->vanish)){
                unset($this->vanish[array_search($player->getName(), $this->vanish)]);
                $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
                $player->setNameTagVisible(true);
                $player->sendMessage($settings->get("VanishDisabled-Message"));
                $sender->sendMessage($this->convert($settings->get("VanishDisabledOther-Message"), $change));
            }
        }else{
            $sender->sendMessage($settings->get("PlayerNotFound"));
            return false;
        }
        return false;
    }

    public function convert(string $string, $change): string{
        $string = str_replace("{player}", $change, $string);
        return $string;
	}
}