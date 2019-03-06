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

class HealCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("heal", "AdvancedCommands", "/heal <player>");
		$this->setPermission("advanced.heal.use");
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
            if($player2 == null) {
                $sender->sendMessage($settings->get("PlayerNotFound"));
                return true;
            }
            $hom = $player2->getName();
            $player2->setHealth(20);
            $player2->sendMessage($settings->get("Heal-Message"));
            $sender->sendMessage($this->convert($settings->get("HealOther-Message"), $hom));
        } else {
            $sender->setHealth(20);
            $sender->sendMessage($settings->get("Heal-Message"));
        }
        return false;
    }

    public function convert(string $string, $hom): string{
        $string = str_replace("{player}", $hom, $string);
        return $string;
	}
}