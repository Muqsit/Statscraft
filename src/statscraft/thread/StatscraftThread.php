<?php

declare(strict_types=1);

namespace statscraft\thread;

use pocketmine\Thread;
use pocketmine\utils\MainLogger;

use statscraft\thread\updates\StatUpdate;

class StatscraftThread extends Thread{

	public const UPDATE_INTERVAL =  10 ** 6;

	/** @var Statistics */
	private $statistics;

	/** @var StatUpdate[] */
	private $updates;

	/** @var MainLogger */
	private $logger;

	/** @var bool */
	private $isRunning = true;

	public function __construct(Statistics $statistics){
		StatUpdate::init($this);
		$this->statistics = serialize($statistics);
		$this->updates = new \Threaded;
		$this->logger = MainLogger::getLogger();
	}

	public function push(StatUpdate $update) : void{
		$this->updates[] = serialize($update);
	}

	public function run() : void{
		$this->registerClassLoader();

		$statistics = unserialize($this->statistics);
		unset($this->statistics);

		$connector = new StatscraftConnector($statistics->getSecret());

		while($this->isRunning){
			while($update = $this->updates->shift()){
				unserialize($update)->apply($statistics);
			}

			$result = $connector->setStatistics($statistics);
			if(isset($result["error"]) || !($result["success"] ?? false)){
				$this->logger->error("Statscraft halted due to an error (" . ($result["error"] ?? "") . ")");
				break;
			}

			$statistics->tick();
			$this->sleep();
		}
	}

	public function sleep() : void{
		$this->synchronized(function(){
			if($this->isRunning){
				$this->wait(self::UPDATE_INTERVAL);
			}
		});
	}

	public function stop() : void{
		StatUpdate::destroy($this);
		$this->isRunning = false;
		$this->synchronized(function(){
			$this->notify();
		});
	}
}