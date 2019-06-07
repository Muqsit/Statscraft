<?php

declare(strict_types=1);

namespace statscraft\thread;

use pocketmine\plugin\Plugin;

class StatscraftPluginObject implements \JsonSerializable{

	public static function fromPlugin(Plugin $plugin) : StatscraftPluginObject{
		$description = $plugin->getDescription();
		return new StatscraftPluginObject(
			$description->getName(),
			$description->getVersion(),
			$description->getWebsite()
		);
	}

	/** @var string */
	protected $name;

	/** @var string */
	protected $version;

	/** @var string */
	protected $website;

	public function __construct(string $name, string $version, string $website = ""){
		$this->name = $name;
		$this->version = $version;
		$this->website = $website;
	}

	public function jsonSerialize() : array{
		return [
			"name" => $this->name,
			"version" => $this->version,
			"website" => $this->website
		];
	}
}