<?php

function __autoload($class){
	$class = root.$class.".php";

	if (!file_exists($class)) {
		throw new Exception("Filepath '{$class}' not found :(");
	}
	require_once $class;
}