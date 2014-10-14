<?php

class Shopware_Components_ArticleImport {
	/* utilize batch mode - just a test really. Shopware will just iterate over the data and call a function to create the individual products */
	public function createProducts($data, $apiClient)
	{
		try {
			foreach($data as $d)
			{
				/*
				$updateImages = array(
					'images' => array(
						array()
					),
				);
				$apiClient->put('articles/'.$d['ordernumber'].'?useNumberAsId=true', $apiClient->put(), $updateImages);
				*/
				

				$articleResource = \Shopware\Components\Api\Manager::getResource('article');
				
				try
				{
					$article = $articleResource->getOneByNumber($d['mainDetail']['number']);
					$this->deleteImages($article['id'], $articleResource);
				} catch(Exception $e)
				{
					#throw $e;
				}
			}
			
		} catch(Exception $e) {
			throw $e;
		}
		try {
			$apiClient->put('articles/', $data);
		} catch(Exception $e) {
			throw $e;
		}
	}

	public function deleteImages($id, $articleResource)
	{
		$articleRepository = $articleResource->getRepository();
		$result = $articleRepository->getArticleImagesQuery($id)->getResult();
		
		foreach ($result as $imageModel) {		
			Shopware()->Models()->remove($imageModel);
			Shopware()->Models()->remove($imageModel->getMedia());
		}
		Shopware()->Models()->flush();
        return true;
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