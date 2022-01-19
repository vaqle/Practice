<?php
namespace vale\sage\practice\database;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use poggit\libasynql\SqlError;
use vale\sage\practice\database\queries\PracticeQueries;
use vale\sage\practice\Loader;

class PracticeDatabase
{
	/** @var DataConnector $database */
	private DataConnector $database;

	public function __construct(Loader $plugin)
	{
		try {
			$this->database = libasynql::create($plugin, array('type' => 'mysql',
				'mysql' =>
					['host' => "na02-db.cus.mc-panel.net", 'username' => "db_383699", 'password' => "58a8761227", 'schema' => "db_383699"],
				'worker-limit' => 4),
				['mysql' => 'mysql.sql']);
			echo("[Practice Database] has been established.\n");
			$this->initTables();
		} catch (SqlError $exception) {
			Loader::getInstance()->getLogger()->info($exception->getMessage());
		}
	}

	/**
	 * @return DataConnector
	 */
	public function getDatabase(): DataConnector{
		return $this->database;
	}

	  //Init Tables
	   public function initTables(){
       $this->database->executeGenericRaw(PracticeQueries::PLAYER_TABLE);
	   $this->database->executeGenericRaw(PracticeQueries::ELO_TABLE);
	}
}