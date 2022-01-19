<?php
namespace vale\sage\practice\utils\menus\forms;

use form\FormIcon;
use form\MenuForm;
use form\MenuOption;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\practice\Loader;
use vale\sage\practice\utils\IUtils;

class FreeForAllForm extends MenuForm{

	public function __construct()
	{
		$nodebuffworld = Loader::getInstance()->getServer()->getWorldManager()->getWorldByName(IUtils::NODEBUFF_FFA_MAP)->getFolderName();
		$nodebuffplayers = count(Server::getInstance()->getWorldManager()->getWorldByName($nodebuffworld)->getPlayers());
		$options = [
			new MenuOption("§r§7Nodebuff \n §r§7Playing: $nodebuffplayers ",new FormIcon("textures/items/potion_bottle_splash_heal",FormIcon::IMAGE_TYPE_PATH))
		];
		parent::__construct("§r§7Sage Arenas","",$options);
	}
}