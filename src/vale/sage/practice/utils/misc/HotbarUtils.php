<?php
namespace vale\sage\practice\utils\misc;

use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class HotbarUtils{

	const TAG_FFA = "FFA";

	const TAG_DUELS_RANKED = "DUELS_ranked";

	const TAG_DUELS_UNRANKED = "DUELS_ranked";

	const TAG_PARTY = "PARTY";

	const TAG_EVENTS = "EVENTS";

	const TAG_BOARD = "BOARDS";

	const TAG_SETTINGS = "SETTINGS";


	/**
	 * @param Player $player
	 * @param string $type
	 */
	public static function sendHotBar(Player $player, string $type = "lobby"): void{
		switch ($type){
			case "lobby":
				$unranked = ItemFactory::getInstance()->get(ItemIds::IRON_SWORD);
				$unranked->setCustomName("§r§dUnranked Queue §r§7(Right Click)");
				$unranked->getNamedTag()->setString(self::TAG_DUELS_UNRANKED,"true");

				$ranked = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SWORD);
				$ranked->setCustomName("§r§dRanked Queue §r§7(Right Click)");
				$ranked->getNamedTag()->setString(self::TAG_DUELS_RANKED,"true");

				$ffa = ItemFactory::getInstance()->get(ItemIds::STONE_SWORD);
				$ffa->setCustomName("§r§dFree For All §r§7(Right Click)");
				$ffa->getNamedTag()->setString(self::TAG_FFA,"true");

				$party = ItemFactory::getInstance()->get(ItemIds::NAMETAG);
				$party->setCustomName("§r§dCreate Party §r§7(Right Click)");
				$party->getNamedTag()->setString(self::TAG_PARTY,"true");

				$events = ItemFactory::getInstance()->get(ItemIds::ENDER_EYE);
				$events->setCustomName("§r§dHost Events §r§7(Right-Click)");
				$events->getNamedTag()->setString(self::TAG_EVENTS,"true");

				$leaderboards = ItemFactory::getInstance()->get(ItemIds::EMERALD);
				$leaderboards->setCustomName("§r§dView Leaderboards §r§7(Right-Click)");
				$leaderboards->getNamedTag()->setString(self::TAG_BOARD,"true");

				$settings = ItemFactory::getInstance()->get(ItemIds::SKULL);
				$settings->setCustomName("§r§dEdit Settings §r§7(Right-Click)");
				$settings->getNamedTag()->setString(self::TAG_SETTINGS,"true");


				$player->getInventory()->setContents([
					0 => $unranked,
					1 => $ranked,
					2 => $ffa,
					4 => $party,
					6 => $events,
					7 => $leaderboards,
					8 => $settings
				]);
				break;
		}
	}
}