<?php
namespace vale\sage\practice\task;
use pocketmine\scheduler\Task;
use vale\sage\practice\Loader;

abstract class SageTask extends Task{

	const SCOREBOARD = 1;
	const DUELS = 2;

	const STOPPED = 0;
	const RUNNING = 1;

	public Loader $plugin;

	/** @var int $status */
	public int $status = self::STOPPED;

	/**
	 * @param int $id
	 */
	public function __construct(public int $id){
		$this->plugin = Loader::getInstance();
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	public function setStatus(int $status = self::RUNNING): void{
		$this->status = $status;
		if($status === self::STOPPED) $this->end();
	}

	public function isRunning(): bool{
		return $this->getStatus() === self::RUNNING;
	}

	final public function onRun(): void
	{
		if($this->isRunning()) $this->run();
	}

	abstract public function run(): void;

	/** Called when the task turns off */
	abstract public function end(): void;

	abstract public function getPeriod(): int;


	public function getStatus(): int{
		return $this->status;
	}
}