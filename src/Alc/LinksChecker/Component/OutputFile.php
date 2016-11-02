<?php

namespace Alc\LinksChecker\Component;

class OutputFile {

	private $filename;
	private $separator;

	public function __construct($filename, $separator = "\t") {

		$this->filename = $filename;
		$this->separator = $separator;

		// Erase old file
		if(file_exists($filename))
			unlink($filename);
	}

	public function append($data) {

		file_put_contents($this->filename, implode($this->separator, $data)."\n", FILE_APPEND);
	}
}