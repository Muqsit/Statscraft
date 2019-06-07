<?php

declare(strict_types=1);

namespace statscraft\thread;

use pocketmine\level\Level;

class StatscraftWorldObject implements \JsonSerializable{

	public static function fromLevel(Level $level) : StatscraftWorldObject{
		return new StatscraftWorldObject(
			$level->getFolderName(),
			count($level->getChunks()),
			count($level->getEntities())
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