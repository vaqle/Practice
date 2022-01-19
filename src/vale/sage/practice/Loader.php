<?php
namespace vale\sage\practice;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use vale\sage\practice\changelogs\Changelogs;
use vale\sage\practice\database\PracticeDatabase;
use vale\sage\practice\duels\DuelManager;
use vale\sage\practice\duels\task\DuelTask;
use vale\sage\practice\handlers\ItemsHandler;
use vale\sage\practice\handlers\PlayerHandler;
use vale\sage\practice\miscellaneous\MiscLoader;
use vale\sage\practice\session\PracticeSession;
use vale\sage\practice\task\SageTask;
use vale\sage\practice\task\types\ScoreboardTask;
use vale\sage\practice\utils\IUtils;

class Loader extends PluginBase{

    /** @var array $practiceSessions **/
    public array $practiceSessions = [];

	/** @var Loader|null $instance **/
	public static ?Loader $instance = null;

	/** @var PracticeDatabase $database **/
	public PracticeDatabase $database;

	/** @var Changelogs $changelogs */
	public Changelogs $changelogs;

   /** @var MiscLoader $miscLoader */
	public MiscLoader $miscLoader;

	public DuelManager $duelManager;

    public function onEnable(): void
	{
		if (!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);
		@mkdir($this->getDataFolder() . "sessions");
		if (self::$instance === null) {
			self::$instance = $this;
		}
		Loader::getInstance()->getServer()->getWorldManager()->loadWorld("ffa_nodebuff");
		new PracticeListener($this);
		$this->database = new PracticeDatabase($this);
		$this->launchTasks();
		$this->changelogs = new Changelogs();
		$this->initiateHandlers();
		$this->getServer()->getWorldManager()->loadWorld(IUtils::NODEBUFF_FFA_MAP);
		$this->miscLoader = new MiscLoader();
		$this->duelManager = new DuelManager();
		$this->getLogger()->info("
   _____                        ____                      __   _           
  / ___/ ____ _ ____ _ ___     / __ \ _____ ____ _ _____ / /_ (_)_____ ___ 
  \__ \ / __ `// __ `// _ \   / /_/ // ___// __ `// ___// __// // ___// _ \
 ___/ // /_/ // /_/ //  __/  / ____// /   / /_/ // /__ / /_ / // /__ /  __/
/____/ \__,_/ \__, / \___/  /_/    /_/    \__,_/ \___/ \__//_/ \___/ \___/ 
             /____/                                                        

  \n \n - by vaqle \n Enabling Modules.");
	}
	public function onDisable(): void
	{
		foreach (Server::getInstance()->getOnlinePlayers() as $player){
			if($session = $this->practiceSessions[$player->getName()]){
				if($session instanceof PracticeSession){
					$session->save();
				}
				$this->database?->getDatabase()->waitAll();
				$this->database->getDatabase()->close();
			}
		}
	}


	private function launchTasks() : void{
		foreach([
					ScoreboardTask::class,
			        DuelTask::class

				] as $class){
			$c = new $class();
			if($c instanceof SageTask){
				$this->getScheduler()->scheduleRepeatingTask($c, $c->getPeriod());
				$c->setStatus(SageTask::RUNNING);
			}
		}
	}


	private function initiateHandlers(): void{
		new PlayerHandler($this);
		new ItemsHandler($this);
	}

	/**
	 * @return PracticeDatabase
	 */
	public function getPracticeDatabase(): PracticeDatabase{
		return $this->database;
	}

	public function getDuelsManager(): DuelManager{
		return $this->duelManager;
	}

	public function getMiscLoader(): MiscLoader{
		return $this->miscLoader;
	}

	/**
	 * @return array
	 */
	public function getPracticeSessions(): array{
		return $this->practiceSessions;
	}

	public function getChangeLogs(): Changelogs{
		return $this->changelogs;
	}
	/**
	 * @return Loader|null
	 */
    public static function getInstance(): ?Loader{
        return self::$instance;
    }
}