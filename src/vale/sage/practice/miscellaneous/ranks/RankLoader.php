<?php
namespace vale\sage\practice\miscellaneous\ranks;
use pocketmine\player\Player;
use vale\sage\practice\Loader;
use vale\sage\practice\miscellaneous\ranks\rank\Rank;
class RankLoader{

	public function __construct()
	{
		$this->init();
	}

	/** @var array $ranks */
	public array $ranks = [];

	public function registerRank(Rank $rank): void{
		$this->ranks[$rank->rankID] = $rank;
	}

	public function getRankFromId(int $id): ?Rank
	{
		return $this->ranks[$id];
	}

	/**
	 * @param Player $player
	 * @param string $string
	 * @param int $rankID
	 * @return string
	 */
	public function replaceVariable(Player $player, string $string, int $rankID):string{
		$session = Loader::getInstance()->practiceSessions[$player->getName()];
		$string = str_replace("{chatformat}",$this->getRankFromId($rankID)->getRankFormat(), $string);
		$string = str_replace("{name}", $player->getName() , $string);
		return $string;
	}

	public function init(): void{
		$this->registerRank(new Rank("WEEENIE",1,["TEST","HM"],"Weenie","{name} {msg}"));
	}
}