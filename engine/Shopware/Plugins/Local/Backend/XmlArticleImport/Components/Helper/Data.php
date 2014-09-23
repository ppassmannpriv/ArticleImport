<?php

class Shopware_Components_Helper_Data
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

	public function parseFiles($files, $ext)
	{
		switch($ext) {
			case 'xml':
			$helper = Shopware()->XmlArticleImportXmlparser();
			$helper->parseXmls($files);
			break;
		}
	}

}