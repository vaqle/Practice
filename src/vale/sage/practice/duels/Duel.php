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

	public const DUEL_END_TIME = 5 * 20;

	public const PHASE = 0;


	public const IN_DUEL = [
		" ",
		" §r§fOpponent: §r§d{fighting}",
		" §r§fDuration: §r§d{duration}",
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

	public function tick(): void{
		$this->duration++;
		if(self::PHASE === 0){
			foreach ($this->getPlayers() as $player){
				$this->sendScoreboard($player,"duel");
			}
		}
		if($this->duration === self::DUEL_END_TIME){
			//TODO DUEL MANAGER END DUEL
			return;
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