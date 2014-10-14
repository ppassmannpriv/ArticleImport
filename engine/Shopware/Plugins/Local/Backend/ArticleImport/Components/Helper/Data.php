<?php

class Shopware_Components_Helper_Data
{

	public function parseFiles($files, $ext)
	{
		$data = false;
		switch($ext) {
			case 'xml':
				$helper = Shopware()->ArticleImportXmlparser();
				$helper->parseXmls($files);
			break;
			case 'csv':
				//$helper = Shopware()->ArticleImportCsvparser();
				foreach($files as $file):
					$csv = Shopware()->ArticleImportCsvparser();
					$csv->delimiter = ';';
					$csv->parse($file);
					$data[] = $csv->data;
				endforeach;
			break;
		}

		return $data;
	}

	public function cleanInput($input)
	{
		$search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);

		$output = preg_replace($search, '', $input);
		return $output;
	}

	public function sanitize($input) {
	    if (is_array($input)) {
	        foreach($input as $var=>$val) {
	            $output[$var] = sanitize($val);
	        }
	    }
	    else {
	        if (get_magic_quotes_gpc()) {
	            $input = stripslashes($input);
	        }
	        $input  = $this->cleanInput($input);
	        $output = mysql_escape_string($input);
	    }

	    //find out about mysql_real_escape_string
	    return $input;
	}

	public function createPost($article, $imgBasePath)
	{
		$dataHelper = Shopware()->ArticleImportData();

		$post = false;
		$active = false; 
		if($article['active'] == '1')
		{
			$active = true;
		}
		$post = array(
			'taxId' => 1,
			'name' => $dataHelper->sanitize($article['name']),
			'supplierId' => 1,
			'active' => $active,
			'description' => $dataHelper->sanitize($article['description']),
			'mainDetail' => array(
				'number' => $article['ordernumber'],
				'active' => $article['active'],
				'weight' => $article['weight']
			),
			'images' => array(
				array(
					'ordernumber' => $article['odernumber'],
					'link' => 'http://placekitten.com/g/500/500',
					'description' => $article['name'],
					'position' => '',
					'main' => 1
				)
			)
		);

		return $post;
	}

}