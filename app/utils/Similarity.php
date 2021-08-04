<?php

namespace Utils;

class Similarity
{
	public static function getArrSentences($filePath)
	{
		$file = fopen($filePath, "r") or die("Unable to open file!");

		$fileArr = array();

		while(!feof($file)) {
		  array_push($fileArr, fgets($file));
		}
		fclose($file);

		return $fileArr;
	}

	public static function compareSentences($arr1, $arr2)
	{
		$simReport = array();

		foreach ($arr1 as $key1 => $value1) {
			foreach ($arr2 as $key2 => $value2) {

				if($value1 == $value2) {
					array_push($simReport, array(
						'index' => $key1,
						'sentence' => $value1,
						'similar_to' => array(
							'index' => $key2,
							'sentence' => $value2
						)
					));
				}
			}
		}

		return $simReport;
	}
}