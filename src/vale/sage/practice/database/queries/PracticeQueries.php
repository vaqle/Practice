<?php
namespace vale\sage\practice\database\queries;

interface PracticeQueries{

	/** @var string */
	public const PLAYER_TABLE = "CREATE TABLE IF NOT EXISTS practiceplayers(xuid VARCHAR (36) PRIMARY KEY, username VARCHAR (16), kills BIGINT DEFAULT 0, deaths BIGINT DEFAULT 0);";
    /** @var string */
	public const LOAD_PLAYER = "SELECT xuid, username, kills, deaths FROM practiceplayers where xuid = ?";
    /** @var string  */
	public const REGISTER_PLAYER = "INSERT INTO practiceplayers(xuid, username) VALUES(?, ?)";
     /** @var string  */
	public const REGISTER_ELO_PLAYER = "INSERT INTO elo(username, nodebuff, boxing, gapple) VALUES (?, ?, ?, ?)";
	/** @var string  */
	public const LOAD_ELO = "SELECT username, nodebuff, boxing, gapple from elo where username = ?";
    /** @var string  */
	public const ELO_TABLE = "CREATE TABLE IF NOT EXISTS elo(username VARCHAR (36) PRIMARY KEY, nodebuff BIGINT default 1000,
    boxing BIGINT default 1000, gapple BIGINT default 1000);";

}