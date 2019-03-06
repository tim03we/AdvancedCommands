<?php

declare(strict_types=1);

namespace tim03we\advancedcommands\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\Config;
use pocketmine\entity\Entity;
use pocketmine\item\Tool;
use pocketmine\item\Armor;
use pocketmine\inventory\PlayerInventory;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\item\enchantment\Enchantment;
use tim03we\advancedcommands\Main;

class RepairCommand extends Command {

    public $vanish = array();
	
	public function __construct(Main $plugin) {
		parent::__construct("repair", "AdvancedCommands", "/repair");
		$this->setPermission("advanced.repair.use");
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
        $messages = new Config($this->plugin->getDataFolder() . "messages.yml", Config::YAML);
        $item = $sender->getInventory()->getItemInHand();
			if($item instanceof Armor or $item instanceof Tool){
				$id = $item->getId();
				$meta = $item->getDamage();
				$sender->getInventory()->removeItem(Item::get($id, $meta, 1));
				$newitem = Item::get($id, 0, 1);
				if($item->hasCustomName()){
					$newitem->setCustomName($item->getCustomName());
					}
				if($item->hasEnchantments()){
					foreach($item->getEnchantments() as $enchants){
					    $newitem->addEnchantment($enchants);
					}
                }
                $iname = $item->getName();
				$sender->getInventory()->addItem($newitem);
				$sender->sendMessage($this->convert($messages->get("RepairSuccess-Message"), $iname));
				return true;
			} else {
                $sender->sendMessage($messages->get("CantRepair-Message"));
                return false;
            }
        return false;
        }

    public function convert(string $string, $iname): string{
        $string = str_replace("{item}", $iname, $string);
        return $string;
	}
}