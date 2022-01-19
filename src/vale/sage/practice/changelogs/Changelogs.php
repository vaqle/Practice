<?php
namespace vale\sage\practice\changelogs;

use pocketmine\player\Player;

class Changelogs{

	/**
	 * @param Player $player
	 */
	public function getMessage(Player $player): void
	{
		$player->sendMessage(str_repeat("§r§7-",32));
		$player->sendMessage("      §r§e§lNA PRACTICE");
		$player->sendMessage("§r§e■ Season §r§d5 §r§e(Started December 11th)");
		$player->sendMessage("§r§e■ To queue a match, §r§dright click the sword.");
		$player->sendMessage("§r§e■ To duel a player, do §r§d/duel [their name]");
		$player->sendMessage("§r§e■ To edit a kit, §r§dtype /editkit.");
		$player->sendMessage("\n");
		$player->sendMessage("§r§e§lWHAT's NEW:");
		$player->sendMessage("§r§e■ 2 NEW Ladders §r§d(Top Fight, Parkour)");
		$player->sendMessage("§r§e■ NEW Leaderboards §r§d(Unranked Titles/Wins)");
		$player->sendMessage("§r§e■ Tons of Bug Fixes + Tweaks)");
		$player->sendMessage(str_repeat("§r§7-",32));
	}
}