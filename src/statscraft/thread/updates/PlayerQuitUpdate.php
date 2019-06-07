<?php

declare(strict_types=1);

namespace statscraft\thread\updates;

use pocketmine\Player;

use statscraft\thread\Statistics;

class PlayerQuitUpdate extends StatUpdate{

	/** @var UUID */
	private $uuid;

	public function __construct(Player $player){
		$this->uuid = $player->getUniqueId();
	}

	public function apply(Statistics $statistics) : void{
		$statistics->removePlayer($this->uuid);
	}
}