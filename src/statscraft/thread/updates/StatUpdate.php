<?php

declare(strict_types=1);

namespace statscraft\thread\updates;

use statscraft\thread\Statistics;
use statscraft\thread\StatscraftThread;

abstract class StatUpdate{

	/** @var StatscraftThread|null */
	private static $thread = null;

	public static function init(StatscraftThread $thread) : void{
		self::$thread = $thread;
	}

	public static function destroy() : void{
		self::$thread = null;
	}

	final public function push() : void{
		self::$thread->push($this);
	}

	abstract public function apply(Statistics $statistics) : void;
}