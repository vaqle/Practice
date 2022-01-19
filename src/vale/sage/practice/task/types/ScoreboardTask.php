<?php
namespace vale\sage\practice\task\types;
use pocketmine\Server;
use vale\sage\practice\Loader;
use vale\sage\practice\task\SageTask;
use vale\sage\practice\utils\IUtils;
use vale\sage\practice\utils\misc\ScoreboardUtils;

class ScoreboardTask extends SageTask{

	public function __construct()
	{
		parent::__construct(SageTask::SCOREBOARD);
	}

	public function run(): void
	{
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			if($player->isOnline() && $player->getWorld()->getFolderName() === IUtils::DEFAULT_LOBBY){
				ScoreboardUtils::sendScoreboard($player);
				return;
			}
		}
	}


	public function end(): void
	{

	}

	public function getPeriod(): int
	{
		return 20;
	}
}