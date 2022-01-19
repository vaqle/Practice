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
		" §r§fYour Ping: §r§d{ping} ms",
		" §r§fTheir Ping: §r§d{fightping} ms",
		" ",
		" §r§dminemen.club",
		"",
	];

	public const WAITING = [
		" ",
		" Opponent: §r§d{fighting} ",
		"",
		" §r§fYour Ping: §r§d{ping} ms",
		" ",
		" §r§fTheir Ping: §r§d{fightping} ms",
		"",
		" §r§dminemen.club",
		"",
	];

	/**
	 * Match Ended sb
	 */
	public const MATCH_ENDED = [
		" ",
		" §r§fMatch ended.",
		"",
		" §r§dminemen.club",
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

	public int $wait = 5;

	public function tick(): void
	{
		if(is_null($this->getPlayers()[0])){
			$this->onEnd();
		}
		if(is_null($this->getPlayers()[1])){
			$this->onEnd();
		}
		if($this->PHASE === 0) {
			--$this->wait;
			foreach ($this->getPlayers() as $player) {
				$this->sendScoreboard($player,"waiting");
			}
			if($this->wait <= 0){
				$this->PHASE = 1;
			}
		}
		if ($this->PHASE === 1) {
			$this->duration++;
			foreach ($this->getPlayers() as $player) {
				$this->sendScoreboard($player, "duel");
			}
		}

		if ($this->PHASE === 2) {
			foreach ($this->getPlayers() as $player) {
				$this->sendScoreboard($player, "ended");
			}
		}

		if ($this->duration === self::DUEL_END_TIME) {
			$this->PHASE = 2;
			foreach ($this->getPlayers() as $player) {
				$this->sendScoreboard($player, "ended");
				--$this->time;
				if ($this->time <= 0) {
					$this->onEnd(false);
				}
			}
		}
	}

	/**
	 * @param bool $playersleft
	 */
	public function onEnd(bool $playersleft = true): void{
		if(!$playersleft){
		Loader::getInstance()->getDuelsManager()->removeMatch($this);
		foreach ($this->getPlayers() as $player) {
			if ($player->isOnline()) {
				PlayerUtils::reset($player);
				Loader::getInstance()->getDuelsManager()->removeDuelPlayer($this->getPlayers());
			}
		}
			if($playersleft){
				Loader::getInstance()->getDuelsManager()->removeMatch($this);
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
			case "waiting":
				PlayerUtils::makeScoreboard($player, IUtils::LOBBY_PREFIX_SCOREBOARD, "waiting");
				$i = 1;
				foreach (self::WAITING as $line) {
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
		$string = str_replace("{ping}", $session->getPlayer()->getNetworkSession()->getPing(), $string);
		if($session->getFighting() !== null) {
			$string = str_replace("{fightping}", $session->getFighting()->getPlayer()->getNetworkSession()->getPing(), $string);
		}
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
