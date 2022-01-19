<?php

namespace vale\sage\practice\duels\kit;


abstract class DuelKit{

	public string $kit = "";

	public function __construct(string $kit){
		$this->kit = $kit;
	}

	public function getKitName(): string{
		return $this->kit;
	}

	public abstract function getArmorItems(): array;

	public abstract function getInventoryItems(): array;
}