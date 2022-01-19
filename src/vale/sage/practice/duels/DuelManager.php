<?php
namespace vale\sage\practice\duels;

use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\practice\duels\task\DuelTask;
use vale\sage\practice\duels\types\MatchedPlayers;
use vale\sage\practice\Loader;
use vale\sage\practice\session\PracticeSession;
use vale\sage\practice\utils\IUtils;

class DuelManager
{
	/** @var array $duels */
	public array $duels = [];

	/** @var array $matchedObjects */
	public array $matchedObjects = [];

	/** @var array $duelPlayers */
	public array $duelPlayers = [];

	/**
	 * @param Player $player
	 * @param Player $opponet
	 * @param string $queue
	 */
	public function createDuel(Player $player, Player $opponet, string $queue): void
	{
		$matched = new MatchedPlayers($player, $opponet, $queue, true);
		$this->duelPlayers[] = $matched->getPlayer()->getName();
		$this->duelPlayers[] = $matched->getOpponet()->getName();
		$this->getSessions([$matched->getOpponet(), $matched->getPlayer()])->setMatchId($matched->getMatchId());
		$this->matchedObjects[$matched->getMatchId()] = $matched;
	}

	public function matchPlayers(): void{
		$matched = $this->matchedObjects;
		foreach ($this->matchedObjects as $object){
			if($object instanceof MatchedPlayers){
				//TODO make system to flipping QUEUE Players Together & stuff
				return;
			}
		}
	}

	public function startDuel(MatchedPlayers $matchedPlayers): void{
		$duel = new Duel($matchedPlayers,"lol");
		$session1 = Loader::getInstance()->practiceSessions[$matchedPlayers->getPlayer()->getName()];
		$session2 = Loader::getInstance()->practiceSessions[$matchedPlayers->getOpponet()->getName()];
		if(!$session1 instanceof PracticeSession && !$session2 instanceof PracticeSession) return;
		$session1->setFighting($session2);
		$session2->setFighting($session1);
		$this->duels[] = $duel;
		$session1->getPlayer()->teleport(Loader::getInstance()->getServer()->getWorldManager()->getWorldByName(IUtils::NODEBUFF_FFA_MAP)->getSpawnLocation());
		unset($this->matchedObjects[$matchedPlayers->getMatchId()]);
	}

	/**
	 * @param $object
	 */
	public function removeMatch($object): void
	{
		$value = array_search($object,$this->duels);
		unset($this->duels[$value]);
	}

	/**
	 * @param array $players
	 */
	public function removeDuelPlayer(array $players): void{
		foreach ([$players] as $pobject){
			if($pobject instanceof Player){
				$name = $pobject->getName();
				$true = array_search($name, $this->duelPlayers);
				unset($this->duelPlayers[$true]);
			}
		}
	}

	/**
	 * @param string $name
	 * @return Player|null
	 */
	public function getDuelPlayer(string $name): ?Player{
		$value = array_search($name, $this->duelPlayers);
		return Server::getInstance()->getPlayerExact($this->duelPlayers[$value]);
	}

	public function getDuels(): array{
		return $this->duels;
	}

	/**
	 * @param array $players
	 * @return PracticeSession
	 */
	public function getSessions(array $players): PracticeSession
	{
		foreach ($players as $player) {
			if ($player instanceof Player) {
				$session = Loader::getInstance()->practiceSessions[$player->getName()];
			}
		}
		return $session;
	}

	/**
	 * @param int $id
	 * @return MatchedPlayers|null
	 */
	public function getMatchFromId(int $id): ?MatchedPlayers
	{
		return $this->matchedObjects[$id];
	}

	/**
	 * @param string $player
	 * @return bool
	 */
	public function inDuel(string $player): bool
	{
		return in_array($player, $this->duelPlayers);
	}

	/**
	 * @param Player $player
	 * @param int|null $id
	 * @return MatchedPlayers|null
	 */
	public function findQueuedMatch(Player $player, int $id = null): ?MatchedPlayers
	{
		$session = Loader::getInstance()->practiceSessions[$player->getName()];
		if (!$session instanceof PracticeSession) return null;
		if ($this->inDuel($player)) {
			$match = $this->getMatchFromId($session->getMatchId());
		}
		return $match;
	}
}
