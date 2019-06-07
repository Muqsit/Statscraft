<?php

declare(strict_types=1);

namespace statscraft;

use pocketmine\scheduler\Task;
use pocketmine\Server;

use statscraft\thread\updates\TPSUpdate;

class StatscraftTask extends Task{

	/** @var Server */
	private $server;

	/** @var TPSUpdate */
	private $tps;

	public function __construct(){
		$this->server = Server::getInstance();
		$this->tps = new TPSUpdate($this->server->getTicksPerSecond());
	}

	public function onRun(int $currentTick) : void{
		$this->tps->set($this->server->getTicksPerSecond())->push();
	}
}