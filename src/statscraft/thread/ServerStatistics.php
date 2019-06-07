<?php

declare(strict_types=1);

namespace statscraft\thread;

use pocketmine\utils\UUID;

class ServerStatistics implements \JsonSerializable {

	/** @var string[] */
	protected $players = [];

	/** @var float */
	protected $tps = 19.99;

	/** @var int */
	protected $ram_max = 0;

	/** @var int */
	protected $ram_allocated = 0;

	/** @var int */
	protected $ram_free = 0;

	/** @var StatscraftWorldsList */
	protected $worlds;

	public function __construct(){
		$this->worlds = new StatscraftWorldsList();
	}

	public function getWorldsList() : StatscraftWorldsList{
		return $this->worlds;
	}

	public function addPlayer(StatscraftPlayer $player) : void{
		$this->players[$player->getUniqueId()->toString()] = $player->getUsername();
	}

	public function removePlayer(UUID $uuid) : void{
		unset($this->players[$uuid->toString()]);
	}

	public function setTPS(float $tps) : ServerStatistics{
		$this->tps = $tps;
		return $this;
	}

	public function setRAMUsage(int $max, int $alloc, int $free) : ServerStatistics{
		$this->ram_max = $max;
		$this->ram_alloc = $alloc;
		$this->ram_free = $free;
		return $onUpdate;
	}

	public function setWorlds(array $worlds) : ServerStatistics{
		$this->worlds = $worlds;
		return $this;
	}

	public function jsonSerialize() : array{
		return [
			"date" => time(),
			"players" => $this->players,
			"tps" => $this->tps,
			"cpu" => sys_getloadavg()[0],
			"ramMax" => $this->ram_max,
			"ramAllocated" => $this->ram_allocated,
			"ramFree" => $this->ram_free,
			"worlds" => $this->worlds
		];
	}
}