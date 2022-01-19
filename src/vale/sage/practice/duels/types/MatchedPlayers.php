<?php
namespace vale\sage\practice\duels\types;

use pocketmine\player\Player;
use vale\sage\practice\Loader;
use vale\sage\practice\session\PracticeSession;

class MatchedPlayers{

	protected ?Player $player;

	protected ?Player $opponet;

	public string $queuedType;

	public bool $rankedMatch =  false;

	public $matchId = 0;

	/**
	 * @param Player $player
	 * @param Player $opponet
	 * @param string $queuedType
	 * @param bool $rankedMatch
	 */
	public function __construct(Player $player, Player $opponet, string $queuedType, bool $rankedMatch){
		$this->player = $player;
		$this->opponet = $opponet;
		$this->queuedType = $queuedType;
		$this->rankedMatch = $rankedMatch;
		$this->matchId = rand(1,10000000);
	}

	public function getPlayer(): Player{
		return $this->player;
	}

	public function getOpponet(): Player
	{
		return $this->opponet;
	}

	public function getMatchId(): int{
		return $this->matchId;
	}

	/**
	 * @return string
	 */
	public function getQueueType(): string{
		return $this->queuedType;
	}

	/**
	 * @param MatchedPlayers $matchedPlayers
	 * @return bool
	 */
	public function hasSameQueue(MatchedPlayers $matchedPlayers): bool
	{
		$bool = false;
		if ($this->getPlayer()->getName() === $matchedPlayers->getPlayer()->getName() && $this->getOpponet()->getName() === $matchedPlayers->getOpponet()->getName()) {
			$value = $this->getQueueType() === $matchedPlayers->getQueueType();
		}
		return $value;
	}
}