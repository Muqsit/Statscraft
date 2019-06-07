<?php

declare(strict_types=1);

namespace statscraft\thread\updates;

use pocketmine\Player;

use statscraft\thread\Statistics;
use statscraft\thread\StatscraftPlayer;

class PlayerJoinUpdate extends StatUpdate{

	/** @var StatscraftPlayer */
	private $player;

	public function __construct(Player $player){
		$this->player = StatscraftPlayer::fromPlayer($player);
	}

	public function apply(Statistics $statistics) : void{
		$statistics->addPlayer($this->player);
	}
}