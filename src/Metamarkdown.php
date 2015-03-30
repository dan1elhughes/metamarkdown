<?php

namespace xes;
class Metamarkdown {

	private $parsedown;
	private $separator = "\n\n=-=\n\n";

	public function __construct($parsedown) {
		$this->parsedown = $parsedown;
	}

	public function text($text) {
		return $this->parsedown->text($text);
	}

	public function metaVal($text) {
		if ($text == 'true' | $text == 'false') {
			return ($text == 'true');
		}

		if (is_numeric($text)) {
			return (int) $text;
		}

		return $text;
	}

	public function metadata($text) {
		$ret = array();

		foreach (explode("\n", $text) as $line) {
			$colonSplit = explode(": ", $line);
			$tagName = $colonSplit[0];
			$values = $colonSplit[1];

			$CSVs = explode(", ", $values);

			if (count($CSVs) == 1) {
				$ret[$tagName] = $this->metaVal($CSVs[0]);
			} else {
				$ret[$tagName] = array();

				foreach ($CSVs as $CSV) {
					$ret[$tagName][] = $this->metaVal($CSV);
				}
			}
		}

		return $ret;
	}

	public function read($text, &$metadataOut = false, &$textOut = false) {
		if ($textOut !== false && $metadataOut !== false) {

			$split = explode($this->separator, $text);

			$metadataOut = $this->metadata($split[0]);
			$textOut = $this->text($split[1]);
		} else {
			return $this->text($text);
		}

	}

}
?>