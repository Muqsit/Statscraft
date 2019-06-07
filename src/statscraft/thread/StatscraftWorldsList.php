<?php

declare(strict_types=1);

namespace statscraft\thread;

class StatscraftWorldsList implements \JsonSerializable{

	/** @var StatscraftWorldObject[] */
	protected $worlds;

	public function __construct(array $worlds = []){
		$this->set($worlds);
	}

	public function set(array $worlds) : void{
		$list = [];
		foreach($worlds as $world){
			$list[$world->getName()] = $world;
		}

		$this->worlds = $list;
	}

	public function jsonSerialize() : array{
		return $this->worlds;
	}
}