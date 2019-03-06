<?php

namespace tim03we\advancedcommands;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use tim03we\advancedcommands\Events\EventListener;
use tim03we\advancedcommands\commands\GamemodeCommand;
use tim03we\advancedcommands\commands\FlyCommand;
use tim03we\advancedcommands\commands\HealCommand;
use tim03we\advancedcommands\commands\FeedCommand;
use tim03we\advancedcommands\commands\VanishCommand;
use tim03we\advancedcommands\commands\RepairCommand;

class Main extends PluginBase implements Listener{

	public function configUpdater(): void {
        $settings = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
        $messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
		if($settings->get("version") !== "1.0.3"){
			rename($this->getDataFolder() . "settings.yml", $this->getDataFolder() . "settings_old.yml");
			$this->saveResource("settings.yml");
            $this->getLogger()->notice("We create a new settings.yml file for you.");
            $this->getLogger()->notice("Because the config version has changed. Your old configuration has been saved as settings_old.yml.");
		}
        if($messages->get("version") !== "1.0.6"){
			rename($this->getDataFolder() . "messages.yml", $this->getDataFolder() . "messages_old.yml");
			$this->saveResource("messages.yml");
            $this->getLogger()->notice("We create a new messages.yml file for you.");
            $this->getLogger()->notice("Because the config version has changed. Your old configuration has been saved as settings_old.yml.");
		}
    }

	public function onEnable() {
        $this->saveResource("settings.yml");
        $this->saveResource("messages.yml");
        $settings = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
        $this->configUpdater();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        if($settings->get("gamemode", true)) {
            Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("gamemode"));
            $this->getServer()->getCommandMap()->register("gamemode", new GamemodeCommand($this));
        }
        if($settings->get("fly", true)) {
            $this->getServer()->getCommandMap()->register("fly", new FlyCommand($this));
        }
        if($settings->get("heal", true)) {
            $this->getServer()->getCommandMap()->register("heal", new HealCommand($this));
        }
        if($settings->get("feed", true)) {
            $this->getServer()->getCommandMap()->register("feed", new FeedCommand($this));
        }
        if($settings->get("vanish", true)) {
            $this->getServer()->getCommandMap()->register("vanish", new VanishCommand($this));
        }
        if($settings->get("repair", true)) {
            $this->getServer()->getCommandMap()->register("repair", new RepairCommand($this));
        }
	}

	public function onDisable() {
	}
}