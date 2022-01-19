<?php
namespace vale\sage\practice\utils\menus\invs;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class FreeForAllInventory{

	/**
	 * @param Player $player
	 */
	public static function open(Player $player): void{
      $menu = InvMenu::create(InvMenuTypeIds::TYPE_HOPPER);
	  $menu->setName("§r§e§lFFA Selectors");
	  $ffa = ItemFactory::getInstance()->get(ItemIds::IRON_AXE);
	  $ffa->setCustomName("§r§e§lFFA");
	  $ffa->setLore([
		  '§r§7Free for all with',
		  '§r§7infinite respawns.',
		  '',
		  '§r§ePlayers: §r§d17',
		  '§r§eClick to play!'
	  ]);
	  $menu->getInventory()->setContents([
		  2 => $ffa
	  ]);
	  $menu->send($player);
	}
}