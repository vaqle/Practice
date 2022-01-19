<?php

namespace vale\sage\practice\utils\misc;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use vale\sage\practice\Loader;
use vale\sage\practice\session\PracticeSession;
use vale\sage\practice\utils\IUtils;

class PlayerUtils{

	/** @var array */
	public static array $scoreboardSessions = [];


	public static function replaceVariable(Player $player, string $string, $match = null): ?string{
		$session = Loader::getInstance()->practiceSessions[$player->getName()];
		if(!$session instanceof PracticeSession) return null;
		$string = str_replace("{online}", count(Loader::getInstance()->getServer()->getOnlinePlayers()), $string);
		$string = str_replace("{playing}", count(Loader::getInstance()->getDuelsManager()->getDuels()) , $string);
		return $string;
	}

	/**
	 * @param Player $player
	 */
	public static function reset(Player $player): void
	{
		$session = Loader::getInstance()->getPracticeSessions()[$player->getName()];
		if ($session instanceof PracticeSession) {
			$session->setFighting(null);
			$session->setMatchId(null);
			$player->getInventory()->clearAll();
			$player->getArmorInventory()->clearAll();
			$player->setDisplayName($player->getName());
			$player->setFlying(false);
			$player->setAllowFlight(false);
			$player->getHungerManager()->setFood(20);
			$player->extinguish();
			$player->setGamemode(GameMode::ADVENTURE());
			$player->getEffects()->clear();
			$player->setHealth(20);
			HotbarUtils::sendHotBar($player);
		}
	}


	public static function playSound(Entity $player, string $sound, $volume = 1, $pitch = 1, int $radius = 5): void
	{
		if ($player instanceof Player) {
			if ($player->isOnline()) {
				$spk = new PlaySoundPacket();
				$spk->soundName = $sound;
				$spk->x = $player->getLocation()->getX();
				$spk->y = $player->getLocation()->getY();
				$spk->z = $player->getLocation()->getZ();
				$spk->volume = $volume;
				$spk->pitch = $pitch;
				$player->getNetworkSession()->sendDataPacket($spk);
			}
		}
	}


	/**
	 * $objective - Id of sb
	 * @param Player $player
	 * @param string $title
	 * @param string $objective
	 * @param string $slot
	 * @param int $order
	 */
	public static function makeScoreboard(Player $player, string $title, string $objective, string $slot = "sidebar", $order = 0)
	{
		if (isset(self::$scoreboardSessions[$player->getName()])) {
			self::removeScoreboard($player);
		}
		$pk = new SetDisplayObjectivePacket();
		$pk->displaySlot = $slot;
		$pk->objectiveName = $objective;
		$pk->displayName = $title;
		$pk->criteriaName = "dummy";
		$pk->sortOrder = 0;
		$player->getNetworkSession()->sendDataPacket($pk);
		self::$scoreboardSessions[$player->getName()] = $objective;
	}

	public static function removeScoreboard(Player $player): void
	{
		$pk = new RemoveObjectivePacket();
		$pk->objectiveName = self::$scoreboardSessions[$player->getName()];
		$player->getNetworkSession()->sendDataPacket($pk);
		unset(self::$scoreboardSessions[$player->getName()]);
	}

	public static function addLine(Player $player, int $score, string $message): void
	{
		if (!isset(self::$scoreboardSessions[$player->getName()])) return;
		$objectiveName = self::$scoreboardSessions[$player->getName()];
		$entry = new ScorePacketEntry();
		$entry->objectiveName = $objectiveName;
		$entry->type = $entry::TYPE_FAKE_PLAYER;
		$entry->customName = $message;
		$entry->score = $score;
		$entry->scoreboardId = $score;
		$pk = new SetScorePacket();
		$pk->type = $pk::TYPE_CHANGE;
		$pk->entries[] = $entry;
		$player->getNetworkSession()->sendDataPacket($pk);
	}

	public static function getScoreboardSessions(): array
	{
		return self::$scoreboardSessions;
	}


}