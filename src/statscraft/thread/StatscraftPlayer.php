<?php

declare(strict_types=1);

namespace statscraft\thread;

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use pocketmine\utils\UUID;

class StatscraftPlayer implements \JsonSerializable{

	public static function fromPlayer(Player $player) : StatscraftPlayer{
		return new StatscraftPlayer(
			$player->getUniqueId(),
			$player->getName(),
			$player->getNetworkSession()->getIp()
		);
	}

	/** @var UUID */
	private $uuid;

	/** @var string */
	private $username;

	/** @var string */
	private $ip;

	/** @var int */
	private $join;

	/** @var int */
	private $quit = 0;

	/** @var string */
	private $version = ProtocolInfo::CURRENT_PROTOCOL;

	public function __construct(UUID $uuid, string $username, string $ip){
		$this->uuid = $uuid;
		$this->username = $username;
		$this->ip = $ip;
		$this->join = time();
	}

	public function getUniqueId() : UUID{
		return $this->uuid;
	}

	public function getUsername() : string{
		return $this->username;
	}

	public function setQuit() : void{
		$this->quit = time();
	}

	public function jsonSerialize() : array{
		return [
			"sessions" => [
				[
					"ip" => $this->ip,
					"quit" => $this->quit,
					"join" => $this->join,
					"version" => $this->version,
					"username" => $this->username
				]
			],
			"uuid" => $this->uuid->toString(),
			"username" => $this->username
		];
	}
}