<?php
namespace vale\sage\practice\miscellaneous;

use vale\sage\practice\Loader;
use vale\sage\practice\miscellaneous\ranks\RankLoader;

class MiscLoader{

	public RankLoader $rankLoader;

	public function __construct(
	){
		$this->init();
	}

	public function init(): void{
		$this->rankLoader = new RankLoader();
	}

	public function getRankLoader(): ?RankLoader{
		return $this->rankLoader;
	}
}