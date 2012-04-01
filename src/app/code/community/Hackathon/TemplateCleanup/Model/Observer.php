<?php

class Hackathon_TemplateCleanup_Model_Observer
{
	public function fetchUsedTemplates()
	{
		$update = Mage::getModel('core/layout_update');
		$design = Mage::getDesign();
		$storeId = 1;
		
		$result = $update->getFileLayoutUpdatesXml(
			$design->getArea(),
			$design->getPackageName(),
			$design->getTheme('layout'),
			$storeId
		);
		
		$data = array();
		foreach($result->xpath('//block') as $handle) {
			Mage::helper('hackathon_templatecleanup')->extractTemplatesFromBlockNodes($handle, $data);
		}
		
		foreach($result->xpath('//action') as $handle) {
			Mage::helper('hackathon_templatecleanup')->extractTemplatesFromActionNodes($handle, $data);
		}
		
		Zend_Debug::dump(count($result->xpath('//block')));
		Zend_Debug::dump(count(array_unique($data)));
		Zend_Debug::dump(array_unique($data));
		Zend_Debug::dump(
			array(
					'area' => $design->getArea(),
					'package' => $design->getPackageName(),
					'theme' => $design->getTheme('layout'),
			)
		);
	}
}