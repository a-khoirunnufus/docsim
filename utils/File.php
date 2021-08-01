<?php

namespace Utils;

use Exception;

class File
{
	public static function readTXT($path2file)
	{
		try
		{
			if ( !file_exists($path2file) ) {
				throw new Exception('File not exist.');
			}

			$file = fopen($path2file, "r");
			if ( !$file ) {
				throw new Exception('File open failed.');
			}

			$text = fread($file, filesize($path2file));
			fclose($file);
			
			return [
				'success' => true,
				'content' => $text
			];

		} catch ( Exception $e ) {
		  	return [
				'success' => false,
				'msg' => $e->getMessage()
			];
		}
	}

	public static function getContents($path2file)
	{
		try
		{
			if ( !file_exists($path2file) ) {
				throw new Exception('File not exist.');
			}

			$content = file_get_contents($path2file);
			if ( !$content ) {
				throw new Exception('File get content failed.');
			}
			
			return [
				'success' => true,
				'content' => $content
			];

		} catch ( Exception $e ) {
		  	return [
				'success' => false,
				'msg' => $e->getMessage()
			];
		}
	}

	public static function write($path2file, $fileContent)
	{
		try
		{
			if ( file_exists($path2file) ) {
				throw new Exception('File already exist.');
			}

			$fp = fopen($path2file, "wb");
			if ( !$fp ) {
				throw new Exception('File open failed.');
			}

			fwrite($fp, $fileContent);
			fclose($fp);
			
			return [
				'success' => true
			];

		} catch ( Exception $e ) {
		  	return [
				'success' => false,
				'msg' => $e->getMessage()
			];
		} 
	}

	public static function writeFromArray($path2file, $arr)
	{
		try
		{
			if ( file_exists($path2file) ) {
				throw new Exception('File already exist.');
			}

			$fp = fopen($path2file, "wb");
			if ( !$fp ) {
				throw new Exception('File open failed.');
			}

			foreach ($arr as $item) {
				fwrite($fp, $item."<SEP>");
			}

			fclose($fp);
			
			return [
				'success' => true
			];

		} catch ( Exception $e ) {
		  	return [
				'success' => false,
				'msg' => $e->getMessage()
			];
		} 
	}
}