<?php

class Shopware_Controllers_Backend_Import
	extends 
	Shopware_Controllers_Backend_ExtJs
{

	public function startAction()
	{
		Shopware()->ArticleImportDispatch()->startImport();
	}
}