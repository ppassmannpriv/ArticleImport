<?php

class Shopware_Components_Helper_Files
{
	public function checkFiles($dir, $extensions = array())
	{ 
		if(empty($extensions) || !is_array($extensions) || !is_dir($dir))
		{
			return false;
		}
		foreach($extensions as $ext)
		{
			if(count(glob($dir.'/*.'.$ext)) > 0 )
			{
				$found[$ext] = 1;
			}
		}

		return (count($found) == count($extensions)) ? true : false;
	}

	public function getFiles($dir, $extension)
	{
		$files = false;
		
		foreach(glob($dir.'/*'.$extension) as $file)
		{
			$files[] = $file;
		}

		return $files;
	}

	public function getDir($filetype, $delta)
	{
		$path = $_SERVER["DOCUMENT_ROOT"];
		if($delta) {
			$filetype .= 'Delta';
		}
		switch($filetype) {
			case 'xml':
				$configString = Shopware()->Plugins()->Backend()->ArticleImport()->Config()->xmlDirPath;
				$path .= $configString;
				break;
			case 'csv':
				$configString = Shopware()->Plugins()->Backend()->ArticleImport()->Config()->csvDirPath;
				$path .= $configString;
				break;
			case 'images':
				$configString = Shopware()->Plugins()->Backend()->ArticleImport()->Config()->imagesDirPath;
				$path = $configString;
				break;
			case 'xmlDelta':
				$configString = Shopware()->Plugins()->Backend()->ArticleImport()->Config()->xmlDirPathDelta;
				$path .= $configString;
				break;
			case 'csvDelta':
				$configString = Shopware()->Plugins()->Backend()->ArticleImport()->Config()->csvDirPathDelta;
				$path .= $configString;
				break;
		}
		
		return $path;
	}
	
}