<?php

namespace Alc\LinksChecker\Component;

class Verbose {

	public function __construct() {
	}

	public function write($text) {

		echo $text;
	}

	public function writeln($text) {

		echo $text, "\n";
	}
}
