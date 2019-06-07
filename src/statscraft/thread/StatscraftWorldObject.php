<?php

declare(strict_types=1);

namespace statscraft\thread;

use pocketmine\world\World;

class StatscraftWorldObject implements \JsonSerializable{

	public static function fromWorld(World $world) : StatscraftWorldObject{
		return new StatscraftWorldObject(
			$world->getFolderName(),
			count($world->getChunks()),
			count($world->getEntities())
		);
	}

	/** @var string */
	protected $name;

	/** @var int */
	protected $chunks;

	/** @var int */
	protected $entities;

	public function __construct(string $name, int $chunks, int $entities){
		$this->name = $name;
		$this->chunks = $chunks;
		$this->entities = $entities;
	}

	public function getName() : string{
		return $this->name;
	}

	public function jsonSerialize() : array{
		return [
			"chunks" => $this->chunks,
			"entities" => $this->entities
		];
	}
}