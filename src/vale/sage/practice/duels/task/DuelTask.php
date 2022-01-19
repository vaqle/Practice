<?php
namespace vale\sage\practice\duels\task;

use pocketmine\scheduler\Task;
use vale\sage\practice\duels\Duel;
use vale\sage\practice\Loader;
use vale\sage\practice\task\SageTask;

class DuelTask extends SageTask
{

	public function __construct()
	{
	 parent::__construct(SageTask::DUELS);
	}

	public function run(): void
	{
		foreach (Loader::getInstance()->getDuelsManager()->getDuels() as $duel){
			if($duel instanceof Duel){
				$duel->tick();
			}
		}
	}

	public function end(): void
	{
		// TODO: Implement end() method.
	}

	public function getPeriod(): int
	{
		return 20;
	}
}