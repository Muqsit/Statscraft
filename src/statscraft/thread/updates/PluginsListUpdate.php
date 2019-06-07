<?php

declare(strict_types=1);

namespace statscraft\thread\updates;

use statscraft\thread\Statistics;
use statscraft\thread\StatscraftPluginObject;

class PluginsListUpdate extends StatUpdate{

	/** @var StatscraftPluginObject[] */
	private $plugins;

	public function __construct(array $plugins){
		$list = [];
		foreach($plugins as $plugin){
			$list[] = StatscraftPluginObject::fromPlugin($plugin);
		}

		$this->plugins = $list;
	}

	public function apply(Statistics $statistics) : void{
		$statistics->setPlugins($this->plugins);
	}
}