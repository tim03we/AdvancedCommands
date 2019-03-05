<?php

declare(strict_types=1);

namespace tim03we\advancedcommands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use tim03we\advancedcommands\Main;

class FlyCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("fly", "AdvancedCommands", "/fly");
		$this->setPermission("advanced.fly.use");
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
            $change = $player2->getName();
            if(!$player2->isCreative()){
                if(!$player2->getAllowFlight()){
                    $player2->setAllowFlight(true);
                    $player2->setFlying(true);
                    $player2->sendMessage($settings->get("FlyEnabled-Message"));
                    $sender->sendMessage($this->convert($settings->get("FlyEnabledOther-Message"), $change));
                }else{
                    $player2->setAllowFlight(false);
                    $player2->setFlying(false);
                    $player2->sendMessage($settings->get("FlyDisabled-Message"));
                    $sender->sendMessage($this->convert($settings->get("FlyDisabledOther-Message"), $change));
                }
            }else{
                $sender->sendMessage($settings->get("PlayerCreativeOther-Message"));
                return false;
            }
        } else {
            if(!$sender->isCreative()){
                if(!$sender->getAllowFlight()){
                    $sender->setAllowFlight(true);
                    $sender->setFlying(true);
                    $sender->sendMessage($settings->get("FlyEnabled-Message"));
                }else{
                    $sender->setAllowFlight(false);
                    $sender->setFlying(false);
                    $sender->sendMessage($settings->get("FlyDisabled-Message"));
                }
            }else{
                $sender->sendMessage($settings->get("PlayerCreative-Message"));
                return false;
            }
        }
        return false;
    }

    public function convert(string $string, $change): string{
        $string = str_replace("{player}", $change, $string);
        return $string;
	}
}