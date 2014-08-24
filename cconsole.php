<?php

register_shutdown_function('cconsole::flush');

class cconsole
{
	private static $logs = array();
	private static $prefix = '[php] ';
	private static $timers = array();


	public static function setprefix($prefix) {
		self::$prefix = $prefix;
	}


	public static function getcallerlocation($offset=0) {
		$trace = debug_backtrace();
		$trace = $trace[$offset];
		return array($trace['file'], $trace['line']);
	}


	public static function log($value) {
		self::_log('log', func_get_args());
	}
	public static function error($value) {
		self::_log('error', func_get_args());
	}
	public static function warn($value) {
		self::_log('warn', func_get_args());
	}
	public static function info($value) {
		self::_log('info', func_get_args());
	}
	public static function debug($value) {
		self::_log('debug', func_get_args());
	}
	private static function _log($type, $values) {
		list($file, $line) = self::getcallerlocation(2);
		$values = array_map('json_encode', $values);
		$values = implode(',', $values);
		self::$logs[] = sprintf('console.%s("%s%s", %d, %s);', 
			$type, self::$prefix, $file, $line, $values);
	}


	public static function time($label) {
		self::$timers[$label] = ceil(microtime(true) * 1000);
	}
	public static function timeEnd($label) {
		if (empty(self::$timers[$label])) {
			throw new ErrorException("Timer \"{$label}\" has not started");
		}
		$time = ceil(microtime(true) * 1000) - self::$timers[$label];
		self::$logs[] = sprintf('console.log("%s%s", "%sms");', self::$prefix, $label, $time);
	}


	public static function clear() {
		self::$logs = array();
	}
	public static function display() {
		echo '<script type="text/javascript">if(window.console){';
		echo implode("\n", self::$logs);
		echo '}</script>';
	}
	public static function flush() {
		self::display();
		self::clear();
	}
}