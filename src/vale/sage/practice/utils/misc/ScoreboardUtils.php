<?php

namespace vale\sage\practice\utils\misc;

use pocketmine\player\Player;
use vale\sage\practice\Loader;
use vale\sage\practice\utils\IUtils;

class ScoreboardUtils
{
	/**
	 * Lobby Scoreboard
	 */
	public const LOBBY_SCOREBOARD = [
		" ",
		" §r§fOnline: §r§d{online}",
		" §r§fPlaying: §r§d{playing}",
		"",
	];


	/**
	 * Match Ended sb
	 */
	public const MATCH_ENDED = [
		" ",
		" §r§fMatch ended.",
		"",
	];

	/**
	 * @param Player $player
	 * @param string $type
	 * @param string|null $match
	 */
	public static function sendScoreboard(Player $player, string $type = "lobby", string $match = null): void
	{
		switch ($type) {
			case "lobby":
				PlayerUtils::makeScoreboard($player, IUtils::LOBBY_PREFIX_SCOREBOARD, "lobby");
				$i = 1;
				foreach (self::LOBBY_SCOREBOARD as $line) {
					$line = PlayerUtils::replaceVariable($player,$line);
					PlayerUtils::addLine($player, $i, $line . str_repeat(" ", 1));
					$i++;
				}
				break;

			case "ended":
				PlayerUtils::makeScoreboard($player, IUtils::LOBBY_PREFIX_SCOREBOARD, "ended");
				$i = 1;
				foreach (self::MATCH_ENDED as $line) {
					$line = PlayerUtils::replaceVariable($player,$line, $match);
					PlayerUtils::addLine($player, $i, $line . str_repeat(" ", 1));
					$i++;
				}
				break;
		}
	}
}