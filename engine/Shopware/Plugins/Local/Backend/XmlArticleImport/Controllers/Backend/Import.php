<?php

class Shopware_Controllers_Backend_Import
	extends 
	Shopware_Controllers_Backend_ExtJs
{
	public function startAction()
	{
		$dataHelper = Shopware()->XmlArticleImportData();
		$filesHelper = Shopware()->XmlArticleImportFiles();
		$apiClient = Shopware()->XmlArticleImportApiClient()->makeConnection();
		
		$dir = $filesHelper->getDir();
		if($filesHelper->checkFiles($dir, array('xml')))
		{
			$files = $filesHelper->getFiles($dir, 'xml');
		}
		
		$dataHelper->parseFiles($files, 'xml');
		
		$data = array(
			'name' => 'Testarticle', 
			'taxId' => 1, 
			'mainDetail' => array(
				'number' => 'SW123456'
			)
		);
		#$this->createProduct($data, $apiClient);
		
	}

	public function createProduct($data, $apiClient)
	{
		try {
			$apiClient->post('articles', $data);
		} catch(Exception $e) {
			throw $e;
		}
	}


}