<?php

declare(strict_types=1);

namespace statscraft;

use pocketmine\event\HandlerList;
use pocketmine\plugin\PluginBase;

use statscraft\thread\StatscraftThread;
use statscraft\thread\Statistics;

class Statscraft extends PluginBase{

	/** @var StatscraftThread|null */
	private $thread = null;

	/** @var StatscraftListener|null */
	private $listener = null;

	/** @var StatscraftTask|null */
	private $task = null;

	public function onEnable() : void{
		$secret = $this->getConfig()->get("secret");
		if(!empty($secret)){
			$this->setSecret($secret);
		}

		if(!$this->getConfig()->get("disable-command")){
			$this->getServer()->getCommandMap()->register($this->getName(), new StatscraftCommand($this));
		}
	}

	public function onDisable() : void{
		$this->setSecret(null);
	}

	public function setSecret(?string $secret) : void{
		if($this->listener !== null){
			HandlerList::unregisterAll($this->listener);
			$this->listener->onUnregister();
			$this->listener = null;
		}

		if($this->task !== null){
			$this->task->getHandler()->cancel();
			$this->task = null;
		}

		if($this->thread !== null){
			$this->thread->stop();
			$this->thread->join();
			$this->thread = null;
		}

		if($secret !== null){				
			$server = $this->getServer();

			$this->thread = new StatscraftThread(
				Statistics::create($secret)
					->setAddress($server->getIp(), $server->getPort())
					->setVersion($server->getVersion(), $server->getPocketMineVersion())
				->setPluginVersion($this->getDescription()->getVersion())
			);
			$this->thread->start();

			$this->listener = new StatscraftListener();
			$this->getServer()->getPluginManager()->registerEvents($this->listener, $this);
			$this->listener->onRegister();

			$this->task = new StatscraftTask();
			$this->getScheduler()->scheduleRepeatingTask($this->task, 20);
		}
	}
}