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

class GamemodeCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("gm", "AdvancedCommands", "/gm <0:1:2:3> <player>", ["gamemode"]);
		$this->setPermission("advanced.gamemode.use");
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
		if(empty($args)) {
			$sender->sendMessage($this->getUsage());
			return true;
        }
        $settings = new Config($this->plugin->getDataFolder() . "messages.yml", Config::YAML);
        if(isset($args[1])){
            $player2 = $this->plugin->getServer()->getPlayer($args[1]);
            if($player2 == null) {
                $sender->sendMessage($settings->get("PlayerNotFound"));
                return true;
            }
            $change = $player2->getName();
            if($args[0] == "0"){
                $player2->setGameMode(0);
                $player2->sendMessage($settings->get("GamemodeChange-Message"));
                $sender->sendMessage($this->convert($settings->get("GamemodeChangeOther-Message"), $change));
            } else if($args[0] == "1"){
                $player2->setGameMode(1);
                $player2->sendMessage($settings->get("GamemodeChange-Message"));
                $sender->sendMessage($this->convert($settings->get("GamemodeChangeOther-Message"), $change));
            } else if($args[0] == "2"){
                $player2->setGameMode(2);
                $player2->sendMessage($settings->get("GamemodeChange-Message"));
                $sender->sendMessage($this->convert($settings->get("GamemodeChangeOther-Message"), $change));
            } else if($args[0] == "3"){
                $player2->setGameMode(3);
                $player2->sendMessage($settings->get("GamemodeChange-Message"));
                $sender->sendMessage($this->convert($settings->get("GamemodeChangeOther-Message"), $change));
            }
        } else {
            if($args[0] == "0"){
                $sender->setGameMode(0);
                $sender->sendMessage($settings->get("GamemodeChange-Message"));
            }else if ($args[0] == "1") {
                $sender->setGameMode(1);
                $sender->sendMessage($settings->get("GamemodeChange-Message"));
            } else if ($args[0] == "2") {
                $sender->setGameMode(2);
                $sender->sendMessage($settings->get("GamemodeChange-Message"));
            } else if ($args[0] == "3") {
                $sender->setGameMode(3);
                $sender->sendMessage($settings->get("GamemodeChange-Message"));
            }
        }
        return false;
    }

    public function convert(string $string, $change): string{
        $string = str_replace("{player}", $change, $string);
        return $string;
	}
}