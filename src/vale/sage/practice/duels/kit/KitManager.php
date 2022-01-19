<?php
namespace vale\sage\practice\duels\kit;

class KitManager{

	public array $kits = [];

	public function init(): void{
		//TODO REGISTER KITS
	}

	public function addKit(DuelKit $kit): void{
		$this->kits[$kit->getKitName()] = $kit;
	}

	public function fromString(string $name): DuelKit{
		return $this->kits[$name];
	}

	public function getKits(): array{
		return $this->kits;
	}

}