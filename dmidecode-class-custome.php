<?php

class DmiDecode {

	public function __construct($json = true) {
		if ($json == true) {
			header('Content-Type: application/json; charset=utf-8');
		}

	}

	public function toJson($command = null) {

		$response = [];
		$segmentIndex = 0;
		$innerSegmentName = "";
		$segmentName = "";
		$exec_command = $command == null ? 'sudo /usr/sbin/dmidecode' : $command;

		exec($exec_command, $output, $rc);

		//Remove first four elements and empty elements from shell output

		$fOpt = array_filter(array_slice($output, 4, -1));
		unset($output);

		foreach ($fOpt as $key => $line) {

			if (preg_match("/^Handle/", $line)) {

				$segmentIndex = $segmentName == trim($fOpt[$key + 1]) . ' ' . $segmentIndex ? ++$segmentIndex : 0;
				$segmentName = trim($fOpt[$key + 1]) . ' ' . $segmentIndex;

				continue;
			}

			preg_match("/([^.]+):\s([^.]+)/", $line, $matches);

			if (!empty($matches)) {

				$innerSegmentName = null;
				$response[$segmentName][trim($matches[1])] = trim($matches[2]);

			} else if (substr($line, -1) == ':') {

				$innerSegmentName = str_replace(':', '', trim($line));
				$response[$segmentName][$innerSegmentName] = [];

			}

			//Sub section

			if ($line[0] == "\t" && !empty($innerSegmentName) && $innerSegmentName != str_replace(':', '', trim($line))) {
				$response[$segmentName][$innerSegmentName][] = trim($line);
			}

		}

		echo json_encode($response);

	}

}

?>