<?php
namespace vale\sage\practice\session;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\practice\database\queries\PracticeQueries;
use vale\sage\practice\Loader;
use vale\sage\practice\utils\IUtils;
use vale\sage\practice\utils\misc\HotbarUtils;
use vale\sage\practice\utils\misc\PlayerUtils;

class PracticeSession
{

	public int $nodebuffelo = 1000;

	public ?PracticeSession $fighting = null;

	public ?int $matchId = null;
	/**
	 * @param Player $player
	 * @param int $kills
	 * @param int $deaths
	 * @param string $username
	 * @param array $data
	 */
	public function __construct(
		private Player $player,
		private int $kills = 0,
		private int $deaths = 0,
		private string $username = "",
		protected array $data = []
	)
	{
		$this->username = $this->player->getName();
		if (file_exists($this->getDirectoryPath())) {
			$this->data = yaml_parse_file($this->getDirectoryPath());
		}
	}
	/**
	 * @param string $option
	 * @param string $value
	 */
	public function setToggleOption(string $option, string $value)
	{
		$this->data["mods"][$option] = $value;
	}

	/**
	 * @param string $option
	 * @param string $string
	 * @return bool
	 */
	public function hasToggleOption(string $option, string $string): bool
	{
		return ($this->data["mods"][$option] === $string);
	}

	public function setFighting(PracticeSession $fighting): void{
		$this->fighting = $fighting;
	}

	public function getFighting(): ?PracticeSession{
		return $this->fighting;
	}

	/**
	 * @param string $option
	 * @return string
	 */
	public function getToggleOption(string $option): string{
		return $this->data["mods"][$option];
	}
	/**
	 * @return Player
	 */
	public function getPlayer(): Player
	{
		return $this->player;
	}

	public function getKills(): int
	{
		return $this->kills;
	}

	public function addKills(): void{
		$this->kills++;
	}

	public function getMatchId(): int{
		return $this->matchId;
	}

	public function setMatchId(int $id): void{
		$this->matchId = $id;
	}

	public function getData(): array{
		return $this->data;
	}

	public function getDirectoryPath(): string
	{
		return Loader::getInstance()->getDataFolder() . "sessions" . DIRECTORY_SEPARATOR . $this->username . ".yml";
	}

	/**
	 * @param bool $async
	 * Saves the Players Data & Sumbits to the Database.
	 */
	public function save(bool $async = true): void{
		$database = Loader::getInstance()->getPracticeDatabase()->getDatabase();
		yaml_emit_file($this->getDirectoryPath(), $this->data);
		if($async){
			$database->executeChangeRaw("REPLACE INTO practiceplayers(xuid, username, kills, deaths) VALUES(?, ?, ?, ?)", [
				$this->getPlayer()->getXuid(), $this->player->getName(), $this->kills, $this->deaths]
			);
			$database->executeChangeRaw("REPLACE INTO elo(username, nodebuff) VALUES(?, ?)", [
				$this->getPlayer()->getName(), $this->nodebuffelo]
			);
		}
	}

	/**
	 * Loads the Players Data
	 */
	public function load(): void
	{
		$database = Loader::getInstance()->getPracticeDatabase()->getDatabase();
		$this->loadElo();
		$database->executeSelectRaw(PracticeQueries::LOAD_PLAYER, [$this->getPlayer()->getXuid()],
			function (array $rows): void {
				foreach ($rows as $row) {
					$this->parsePlayerData($row);
				}
			});
	}

	public function loadElo(): void{
		$database = Loader::getInstance()->getPracticeDatabase()->getDatabase();
		$database->executeSelectRaw(PracticeQueries::LOAD_ELO, [$this->getPlayer()->getName()],
			function (array $rows): void {
				foreach ($rows as $row) {
					$this->parseEloData($row);
				}
			});
	}

	/**
	 * Register a new Player
	 */
	public function register(): void{
		$this->setToggleOption("preference", IUtils::PREFRENCE_MENU);
		$this->setToggleOption("scoreboard", IUtils::ON);
		$this->setToggleOption("showcps", IUtils::OFF);
		$database = Loader::getInstance()->getPracticeDatabase()->getDatabase();
		$database->executeChangeRaw(PracticeQueries::REGISTER_ELO_PLAYER, [$this->player->getName(),100,100,100],function (int $rows): void{
			$this->loadElo();
		});
		$database->executeChangeRaw(PracticeQueries::REGISTER_PLAYER, [$this->getPlayer()->getXuid(), $this->player->getName()],
			 function (int $rows): void{
			$this->load();
		});
	}

	/**
	 * @param array $rows
	 * Converts the data from the array to a value.
	 */
	public function parsePlayerData(array $rows): void{
		$this->kills =  $rows["kills"];
		$this->deaths = $rows["deaths"];
	}

	/**
	 * @param array $rows
	 * Converts the data from the array to a value.
	 */
	public function parseEloData(array $rows): void{
		$this->nodebuffelo = $rows["nodebuff"];
	}
}