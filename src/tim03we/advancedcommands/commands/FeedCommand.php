<?php

declare(strict_types=1);

namespace tim03we\advancedcommands\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use tim03we\advancedcommands\Main;

class FeedCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("feed", "AdvancedCommands", "/feed <player>");
		$this->setPermission("advanced.feed.use");
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
        if(isset($args[0])){
            $player2 = $this->plugin->getServer()->getPlayer($args[0]);
            $player2->setFood(20);
            $hom = $player2->getName();
            $player2->sendMessage($settings->get("Feed-Message"));
            $sender->sendMessage($this->convert($settings->get("FeedOther-Message"), $hom));
        } else {
            $sender->setFood(20);
            $sender->sendMessage($settings->get("Feed-Message"));
        }
        $issuer = $sender->getName();
        return false;
    }

    public function convert(string $string, $hom): string{
        $string = str_replace("{player}", $hom, $string);
        return $string;
	}
}