<?php

class Shopware_Controllers_Backend_Import extends Shopware_Controllers_Backend_ExtJs
{
	public function startAction()
	{
		$dataHelper = Shopware()->XmlArticleImportHelper();
		$apiClient = Shopware()->XmlArticleImportApiClient();
		var_dump($apiClient);
		die('great success');
	}
}