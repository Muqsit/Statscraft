<?php

declare(strict_types=1);

namespace statscraft\thread;

use pocketmine\utils\UUID;

class Statistics implements \JsonSerializable{

	public static function create(...$args) : Statistics{
		return new Statistics(...$args);
	}

	/** @var string */
	protected $secret;

	/** @var ServerStatistics */
	protected $server;

	/** @var StatscraftPlayer[] */
	protected $players = [];

	/** @var string */
	protected $version;

	/** @var string */
	protected $pocketmine_version;

	/** @var string */
	protected $icon = "";

	/** @var string */
	protected $ip;

	/** @var int */
	protected $port;

	/** @var int */
	protected $plugin_version_id;

	/** @var string */
	protected $plugin_version;

	/** @var array */
	protected $plugins = [
		[
			"website" => "https://statscraft.net",
			"name" => "Statscraft",
			"version" => "1.2.0"
		]
	];

	/** @var string[] */
	private $remove_players = [];

	public function __construct(string $secret){
		$this->secret = $secret;
		$this->server = new ServerStatistics();
	}

	public function getSecret() : string{
		return $this->secret;
	}

	public function getServer() : ServerStatistics{
		return $this->server;
	}

	public function setPlayers(array $players) : Statistics{
		$this->players = $players;
		return $this;
	}

	public function addPlayer(StatscraftPlayer $player) : void{
		$this->players[$player->getUniqueId()->toString()] = $player;
		$this->server->addPlayer($player);
	}

	public function removePlayer(UUID $uuid) : void{
		$this->players[$uuid_string = $uuid->toString()]->setQuit();
		$this->remove_players[] = $uuid_string;
		$this->server->removePlayer($uuid);
	}

	public function tick() : void{
		foreach($this->remove_players as $uuid){
			unset($this->players[$uuid]);
		}

		$this->remove_players = [];
	}

	public function setVersion(string $version, string $pocketmine_version) : Statistics{
		$this->version = $version;
		$this->pocketmine_version = $version;
		return $this;
	}

	public function setPluginVersion(string $version, int $version_id = 10100) : Statistics{
		$this->plugin_version_id = $version_id;
		$this->plugin_version = $version;
		return $this;
	}

	public function setPlugins(array $plugins) : Statistics{
		$this->plugins = $plugins;
		return $this;
	}

	public function setIcon(string $icon) : Statistics{
		$this->icon = $icon;
		return $this;
	}

	public function setAddress(string $ip, int $port) : Statistics{
		$this->ip = $ip;
		$this->port = $port;
		return $this;
	}

	public function jsonSerialize() : array{
		return [
			"server" => [$this->server],
			"players" => array_values($this->players),
			"privateKey" => $this->secret,
			"serverTime" => time(),
			"serverVersion" => $this->version,
			"bukkitVersion" => $this->pocketmine_version,
			"serverIcon" => $this->icon,
			"serverIp" => $this->ip . ":" . $this->port,
			"pluginVersionId" => $this->plugin_version_id,
			"pluginVersion" => $this->plugin_version,
			"plugins" => $this->plugins
		];
	}
}