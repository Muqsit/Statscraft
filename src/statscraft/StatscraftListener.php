<?php

declare(strict_types=1);

namespace statscraft;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\plugin\PluginEnableEvent;
use pocketmine\event\world\WorldLoadEvent;
use pocketmine\event\world\WorldUnloadEvent;
use pocketmine\Server;

use statscraft\thread\updates\PlayerJoinUpdate;
use statscraft\thread\updates\PlayerQuitUpdate;
use statscraft\thread\updates\PluginsListUpdate;
use statscraft\thread\updates\WorldsListUpdate;

class StatscraftListener implements Listener{

	public function onRegister() : void{
		foreach(Server::getInstance()->getOnlinePlayers() as $player){
			(new PlayerJoinUpdate($player))->push();
		}

		$this->updatePluginsList();
		$this->updateWorldsList();
	}

	public function onUnregister() : void{
		foreach(Server::getInstance()->getOnlinePlayers() as $player){
			(new PlayerQuitUpdate($player))->push();
		}
	}

	public function updatePluginsList() : void{
		(new PluginsListUpdate(Server::getInstance()->getPluginManager()->getPlugins()))->push();
	}

	public function updateWorldsList() : void{
		(new WorldsListUpdate(Server::getInstance()->getWorldManager()->getWorlds()))->push();
	}

	/**
	 * @param PlayerLoginEvent $event
	 * @priority MONITOR
	 */
	public function onPlayerLogin(PlayerLoginEvent $event) : void{
		(new PlayerJoinUpdate($event->getPlayer()))->push();
	}

	/**
	 * @param PlayerQuitEvent $event
	 * @priority MONITOR
	 */
	public function onPlayerQuit(PlayerQuitEvent $event) : void{
		(new PlayerQuitUpdate($event->getPlayer()))->push();
	}

	/**
	 * @param PluginEnableEvent $event
	 * @priority MONITOR
	 */
	public function onPluginEnable(PluginEnableEvent $event) : void{
		$this->updatePluginsList();
	}

	/**
	 * @param WorldLoadEvent $event
	 * @priority MONITOR
	 */
	public function onWorldLoad(WorldLoadEvent $event) : void{
		$this->updateWorldsList();
	}

	/**
	 * @param WorldUnloadEvent $event
	 * @priority MONITOR
	 */
	public function onWorldUnload(WorldUnloadEvent $event) : void{
		$this->updateWorldsList();
	}
}