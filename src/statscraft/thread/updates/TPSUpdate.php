<?php

declare(strict_types=1);

namespace statscraft\thread\updates;

use statscraft\thread\Statistics;

class TPSUpdate extends StatUpdate{

	/** @var float */
	private $tps;

	public function __construct(float $tps){
		$this->set($tps);
	}

	public function set(float $tps) : TPSUpdate{
		$this->tps = $tps;
		return $this;
	}

	public function apply(Statistics $statistics) : void{
		$statistics->getServer()->setTPS($this->tps);
	}
}