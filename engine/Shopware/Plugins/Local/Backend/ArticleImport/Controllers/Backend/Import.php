<?php

class Shopware_Controllers_Backend_Import
	extends 
	Shopware_Controllers_Backend_ExtJs
{
	public function startAction()
	{
		$dataHelper = Shopware()->ArticleImportData();
		$filesHelper = Shopware()->ArticleImportFiles();
		$apiClient = Shopware()->ArticleImportApiClient()->makeConnection();
		
		$dir = $filesHelper->getDir('csv');

		if($filesHelper->checkFiles($dir, array('csv')))
		{
			$files = $filesHelper->getFiles($dir, 'csv');
		}

		
		$imgBasePath = $filesHelper->getDir('images');
		$data = $dataHelper->parseFiles($files, 'csv');
		//THE CSV FILE SHOULD BE UTF-8 ! THIS IS VERY IMPORTANT. THE API WILL NOT ACCEPT STRINGS IN NON-UTF8

		foreach($data as $partial){
			
			$postArray = false;

			foreach($partial as $article)
			{	
				$i++;
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
					)
				);
				$postArray[] = $post;
			}	

			$this->createProducts($postArray, $apiClient);
		}
		
	}

	/* utilize batch mode - just a test really. Shopware will just iterate over the data and call a function to create the individual products */
	public function createProducts($data, $apiClient)
	{
		try {
			$apiClient->put('articles/', $data);
		} catch(Exception $e) {
			throw $e;
		}
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