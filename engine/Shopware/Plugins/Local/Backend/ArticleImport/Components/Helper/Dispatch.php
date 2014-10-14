<?php

class Shopware_Components_Helper_Dispatch
{

	var $dataHelper = '';
	var $filesHelper = '';
	var $apiClient = '';
	var $importHelper = '';

	public function __construct()
	{
		$this->dataHelper = Shopware()->ArticleImportData();
		$this->filesHelper = Shopware()->ArticleImportFiles();
		$this->apiClient = Shopware()->ArticleImportApiClient()->makeConnection();
		$this->importHelper = Shopware()->ArticleImport();

	}

	public function startImport()
	{
		
		$fileType = Shopware()->Plugins()->Backend()->ArticleImport()->Config()->fileType;

		$dir = $this->filesHelper->getDir($fileType, false);

		if($this->filesHelper->checkFiles($dir, array($fileType)))
		{
			$files = $this->filesHelper->getFiles($dir, $fileType);
		}

		
		$imgBasePath = $this->filesHelper->getDir('images');
		$data = $this->dataHelper->parseFiles($files, $fileType);
		//THE CSV FILE SHOULD BE UTF-8 ! THIS IS VERY IMPORTANT. THE API WILL NOT ACCEPT STRINGS IN NON-UTF8

		foreach($data as $partial){
			
			$postArray = false;

			foreach($partial as $article)
			{	
				$postArray[] = $this->dataHelper->createPost($article, $imgBasePath);
			}	
			
			$this->importHelper->createProducts($postArray, $this->apiClient);
		}
	}

	public function deltaImport()
	{
		$dataHelper = Shopware()->ArticleImportData();
		$filesHelper = Shopware()->ArticleImportFiles();
		$apiClient = Shopware()->ArticleImportApiClient()->makeConnection();
		$importHelper = Shopware()->ArticleImport();
		$fileType = Shopware()->Plugins()->Backend()->ArticleImport()->Config()->fileType;

		$dir = $this->filesHelper->getDir($fileType, true);

		if($this->filesHelper->checkFiles($dir, array($fileType)))
		{
			$files = $this->filesHelper->getFiles($dir, $fileType);
		}

		
		$imgBasePath = $this->filesHelper->getDir('images');
		$data = $this->dataHelper->parseFiles($files, $fileType);
		//THE CSV FILE SHOULD BE UTF-8 ! THIS IS VERY IMPORTANT. THE API WILL NOT ACCEPT STRINGS IN NON-UTF8

		foreach($data as $partial){
			
			$postArray = false;

			foreach($partial as $article)
			{	
				$postArray[] = $this->dataHelper->createPost($article, $imgBasePath);
			}	
			
			$this->importHelper->createProducts($postArray, $this->apiClient);
		}
	}



}

?>