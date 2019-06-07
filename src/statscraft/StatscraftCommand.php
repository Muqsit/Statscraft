<?php

declare(strict_types=1);

namespace statscraft;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat;

use statscraft\thread\StatscraftConnector;
use statscraft\utils\APIException;

class StatscraftCommand extends PluginCommand{

	public function __construct(Statscraft $plugin){
		parent::__construct("statscraft", $plugin);
		$this->setPermission("statscraft.command");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!$this->testPermission($sender)){
			return;
		}

		if(isset($args[0])){
			switch($args[0]){
				case "verify":
					if(isset($args[1])){
						try{
							$connector = new StatscraftConnector($args[1]);
							$result = $connector->validate();
						}catch(APIException $e){
							$sender->sendMessage(TextFormat::RED . "Failed to set secret: " . $e->getMessage());
							return;
						}

						$connector->close();

						$this->getPlugin()->setSecret($args[1]);
						$this->getPlugin()->getConfig()->set("secret", $args[1]);
						$this->getPlugin()->getConfig()->save();
						$sender->sendMessage(TextFormat::GREEN . $result);
						$sender->sendMessage(TextFormat::GREEN . "Statscraft server has been set!");
						return;
					}
					break;
				case "unverify":
					$this->getPlugin()->setSecret(null);
					$this->getPlugin()->getConfig()->set("secret", "");
					$this->getPlugin()->getConfig()->save();
					$sender->sendMessage(TextFormat::GREEN . "Statscraft server has been unverified!");
					return;
			}
		}

		$sender->sendMessage(
			TextFormat::DARK_GRAY . "----[ " . TextFormat::RED . "Statscraft" . TextFormat::DARK_GRAY . "]----" . TextFormat::EOL .
			TextFormat::RED . "/statscraft verify <secret> " . TextFormat::GRAY . "- Verifies and sets the server's secret key" . TextFormat::EOL .
			TextFormat::RED . "/statscraft unverify " . TextFormat::GRAY . "- Resets the server's sercet key and stops uploading statistics"
		);
	}
}