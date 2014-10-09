<?php
class Shopware_Plugins_Backend_ArticleImport_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getCapabilities()
    {
        return array(
            'install' => true,
            'enable' => true,
            'update' => false
        );
    }
 
    public function getLabel()
    {
        return 'Article Import';
    }
 
    public function getVersion()
    {
        return "0.0.1";
    } 
 
    public function getInfo() {
        return array(
            'version' => $this->getVersion(),
            'copyright' => 'Copyright (c) 2014, Pieter Paßmann',
            'label' => $this->getLabel(),
			'autor' => 'Pieter Paßmann',
            'description' => file_get_contents($this->Path() . 'info.txt'),
            'support' => 'http://www.scriptkid.de',
            'link' => 'http://www.scriptkid.de',
            'changes' => array(
                '0.0.1'=>array('releasedate'=>'2014-09-03', 'lines' => array(
                    'First Test'
                ))
            ),
            'revision' => '1'
        );
    }
 
    public function update($version)
    {
        return true;
    }
 
    public function install()
    {
        $this->subscribeEvents();
 
        // -Modul
        /*$this->createMenuItem(array(
            'label' => 'Order Export',
            'controller' => 'viewExport',
            'class' => 'sprite-box-zipper',
            'action' => 'Index',
            'active' => 1,
            'parent' => $this->Menu()->findOneBy('label', 'MENÜ')
        ));
 
		*/
		$this->createConfiguration();
		$this->regControllers();
        return array(
            'success' => true,
            'message' => 'All is well.'
        );

    }

	public function createConfiguration()
	{
		$form = $this->Form();
		$repository = Shopware()->Models()->getRepository('Shopware\Models\Config\Form');
		
		$form->setElement('text', 'xmlDirPath',
			array(
				'label' => 'XML Ordner',
				'value' => 'Wo werden die ImportXMLs abgelegt?',
				'scope' => Shopware\Models\Config\Element::SCOPE_SHOP,
				'description' => 'Ordnerpfad für import',
				'required' => true,
			)
		);

		$form->setParent(
			$repository->findOneBy(
				array('name' => 'Interface')
			)
		);
	}

	private function regControllers()
	{
		$this->registerController('Backend', 'Import');
	}
 
    public function uninstall()
    {
		/*
        $this->Application()->Models()->removeAttribute(
            's_articles_attributes',
            'ppassmann',
            'ExportXml'
        );
 
        $this->getEntityManager()->generateAttributeModels(array(
            's_articles_attributes'
        ));
		*/
        return true;
    }
 
    private function subscribeEvents()
    {
        $this->subscribeEvent(
			'Enlight_Bootstrap_InitResource_ArticleImport',
            'onInitResourceArticleImport'
		);

		$this->subscribeEvent(
            'Enlight_Bootstrap_InitResource_ArticleImportCsvparser',
            'onInitResourceArticleImportCsvparser'
        );

        $this->subscribeEvent(
			'Enlight_Bootstrap_InitResource_ArticleImportXmlparser',
            'onInitResourceArticleImportXmlparser'
		);

		$this->subscribeEvent(
			'Enlight_Bootstrap_InitResource_ArticleImportData',
            'onInitResourceArticleImportData'
		);

		$this->subscribeEvent(
			'Enlight_Bootstrap_InitResource_ArticleImportFiles',
            'onInitResourceArticleImportFiles'
		);

		$this->subscribeEvent(
			'Enlight_Bootstrap_InitResource_ArticleImportApiClient',
            'onInitResourceImportApiClient'
		);
    }

	public function onInitCollection(Enlight_Event_EventArgs $arguments)
	{
		$this->onInitResourceXmlArticleImport($arguments);
	}

	public function onInitResourceArticleImport(Enlight_Event_EventArgs $arguments)
	{
		$this->Application()->Loader()->registerNamespace(
            'Shopware_Components',
            $this->Path() . 'Components/'
        );
 
        $component = new Shopware_Components_ArticleImport();
 
        return $component;
	}

	public function onInitResourceImportApiClient(Enlight_Event_EventArgs $arguments)
    {
        $this->Application()->Loader()->registerNamespace(
            'Shopware_Components',
            $this->Path() . 'Components/Helper/'
        );
 
        $component = new Shopware_Components_Helper_ApiClient();
 
        return $component;
    }

	public function onInitResourceArticleImportXmlparser(Enlight_Event_EventArgs $arguments)
    {

		$this->Application()->Loader()->registerNamespace(
            'Shopware_Components_Helper',
            $this->Path() . 'Components/Helper/'
        );
 
        $component = new Shopware_Components_Helper_Xmlparser();

        return $component;

	}

    public function onInitResourceArticleImportCsvparser(Enlight_Event_EventArgs $arguments)
    {

        $this->Application()->Loader()->registerNamespace(
            'Shopware_Components_Helper',
            $this->Path() . 'Components/Helper/'
        );

        $component = new Shopware_Components_Helper_Csvparser();

        return $component;

    }

	public function onInitResourceArticleImportData(Enlight_Event_EventArgs $arguments)
    {

		$this->Application()->Loader()->registerNamespace(
            'Shopware_Components_Helper',
            $this->Path() . 'Components/Helper/'
        );
 
        $component = new Shopware_Components_Helper_Data();

        return $component;

	}
	
	public function onInitResourceArticleImportFiles(Enlight_Event_EventArgs $arguments)
    {

		$this->Application()->Loader()->registerNamespace(
            'Shopware_Components_Helper',
            $this->Path() . 'Components/Helper/'
        );
 
        $component = new Shopware_Components_Helper_Files();

        return $component;

	}
	
    public function afterInit()
    {
        $this->registerCustomModels();
    }
 
 
}
 