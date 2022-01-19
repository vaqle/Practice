<?php
namespace vale\sage\practice\duels;

use pocketmine\player\Player;
use vale\sage\practice\duels\types\MatchedPlayers;
use vale\sage\practice\Loader;
use vale\sage\practice\session\PracticeSession;
use vale\sage\practice\utils\IUtils;
use vale\sage\practice\utils\misc\PlayerUtils;
use vale\sage\practice\utils\misc\ScoreboardUtils;

class Duel{

    /** @var string $mapName */
	public string $mapName;

	public const DUEL_END_TIME = 20;

	public int  $PHASE = 0;

	public const IN_DUEL = [
		" ",
		" §r§fOpponent: §r§d{fighting}",
		" §r§fDuration: §r§d{duration}",
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

	/** @var int $duration */
	public int $duration = 0;

	public string $mapname;

	/** @var MatchedPlayers $matchedPlayers */
	public MatchedPlayers $matchedPlayers;

	public function __construct(MatchedPlayers $matchedPlayers, string $mapName){
		$this->matchedPlayers = $matchedPlayers;
		$this->mapName = $mapName;
	}

	public int $time = 5;

	public function tick(): void
	{
		if (is_null($this->matchedPlayers->getPlayer()) || is_null($this->matchedPlayers->getOpponet())) {
			Loader::getInstance()->getDuelsManager()->removeMatch($this);
			foreach ($this->getPlayers() as $player) {
				if ($player->isOnline()) {
					PlayerUtils::reset($player);
				}
			}
			return;
		}
		if ($this->PHASE === 0) {
			$this->duration++;
			foreach ($this->getPlayers() as $player) {
				$this->sendScoreboard($player, "duel");
			}
		}

		if ($this->PHASE === 1) {
			foreach ($this->getPlayers() as $player) {
				$this->sendScoreboard($player, "ended");
			}
		}

		if ($this->duration === self::DUEL_END_TIME) {
			$this->PHASE = 1;
			foreach ($this->getPlayers() as $player) {
				$this->sendScoreboard($player, "ended");
				--$this->time;
				if ($this->time <= 0) {
					$this->onEnd();
				}
			}
		}
	}


	public function onEnd(): void{
		Loader::getInstance()->getDuelsManager()->removeMatch($this);
		foreach ($this->getPlayers() as $player) {
			$player->sendMessage("Duel Ended");
			if ($player->isOnline()) {
				PlayerUtils::reset($player);
			}
		}
	}

	public function getMapName(): string{
		return $this->mapName;
	}

	public function getDuration(): int{
		return $this->duration;
	}

	public function sendScoreboard(Player $player, string $type): void
	{
		switch ($type) {
			case "duel":
				PlayerUtils::makeScoreboard($player, IUtils::LOBBY_PREFIX_SCOREBOARD, "duel");
				$i = 1;
				foreach (self::IN_DUEL as $line) {
					$line = $this->replaceVariable($player, $line);
					PlayerUtils::addLine($player, $i, $line . str_repeat(" ", 1));
					$i++;
				}
				break;
			case "ended":
				PlayerUtils::makeScoreboard($player, IUtils::LOBBY_PREFIX_SCOREBOARD, "ended");
				$i = 1;
				foreach (self::MATCH_ENDED as $line) {
					$line = $this->replaceVariable($player, $line);
					PlayerUtils::addLine($player, $i, $line . str_repeat(" ", 1));
					$i++;
				}
				break;
		}
	}

	public function replaceVariable(Player $player, string $string, $match = null): ?string{
		$session = Loader::getInstance()->practiceSessions[$player->getName()];
		if(!$session instanceof PracticeSession) return null;
		$string = str_replace("{duration}", $this->duration, $string);
		if($session->getFighting() !== null) {
			$string = str_replace("{fighting}", $session->getFighting()->getPlayer()->getName(), $string);
		}
		return $string;
	}

	/**
	 * @return array
	 */
	public function getPlayers(): array{
		return [$this->matchedPlayers->getPlayer(), $this->matchedPlayers->getOpponet()];
	}
}