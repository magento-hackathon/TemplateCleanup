<?php
class Hackathon_TemplateCleanup_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function extractTemplatesFromBlockNodes($node, &$data)
	{
		if($node->{block}) {
			$this->extractTemplatesFromBlockNodes($node->{block}, $data);
		}

		if(is_array($node)) {
			foreach($node as $subnode) {
				$this->extractTemplatesFromBlockNodes($subnode, $data);
			}
		}
		else if($node->getAttribute('type') && $node->getAttribute('template')) {
			$data[] = $node->getAttribute('template');
		}
		else if($blockType = $node->getAttribute('type')) {
			try {
				if(preg_match('/sales\/order/', $blockType)) {
					$data[] = preg_replace('/_/', '/', $blockType) . '.phtml';
				}
				else {
					switch($blockType) {
						case 'xmlconnect/customer_giftcardCheck':	break;
						default:
						$className = Mage::getConfig()->getBlockClassName($node->getAttribute('type'));
						$template = $this->fetchTemplateFromClassCode($className);
						if($template) {
							$data[] = $template;
						}
					}
				}
			}
			catch(Exception $e) {
				Mage::logException(new Exception('extract template from block type \'' . $node->getAttribute('type') . '\' failed.'));
			}
		}
			
		return $data;
	}

	public function extractTemplatesFromActionNodes($node, &$data)
	{
		if(is_array($node)) {
			$this->extractTemplatesFromActionNodes($node, $data);
			continue;
		}
		else if($node->getAttribute('method') && $node->getAttribute('method') == 'setTemplate' && (string)$node->{template}) {
			$data[] = (string)$node->{template};
		}

		return $data;
	}
	
	public function fetchTemplateFromClassCode($className)
	{
		$reflClass = new ReflectionClass($className);
		$classCode = file_get_contents($reflClass->getFileName());
		
		$templates = array();
		preg_match_all('/this->setTemplate\(\'(.*)\'\)/', $classCode, $templates);
		
		if(count($templates) > 1 && count($templates[1]) > 0)
		{
			return $templates[1][0];
		}
		elseif($parentClass = $reflClass->getParentClass()) 
		{
			Zend_Debug::dump($parentClass);
			return $this->fetchTemplateFromClassCode($parentClass);
		}
		
		return false;
	}
}