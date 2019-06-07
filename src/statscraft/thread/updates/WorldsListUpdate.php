<?php

declare(strict_types=1);

namespace statscraft\thread\updates;

use statscraft\thread\Statistics;
use statscraft\thread\StatscraftWorldObject;

class WorldsListUpdate extends StatUpdate{

	/** @var StatscraftWorldObject[] */
	private $worlds;

	public function __construct(array $worlds){
		$list = [];
		foreach($worlds as $world){
			$list[] = StatscraftWorldObject::fromWorld($world);
		}

		$this->worlds = $list;
	}

	public function apply(Statistics $statistics) : void{
		$statistics->getServer()->getWorldsList()->set($this->worlds);
	}
}