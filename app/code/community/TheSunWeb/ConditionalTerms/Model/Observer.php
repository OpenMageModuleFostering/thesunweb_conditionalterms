<?php
class TheSunWeb_ConditionalTerms_Model_Observer
{
	public function saveAgreementAttributes(Varien_Event_Observer $observer)
	{
		$agreement = $observer->getAgreement();
		
		$serializedString = serialize($agreement->getConditions()->asArray());
		$attributes = array();
		if (preg_match_all('~s:32:"salesrule/rule_condition_product";s:9:"attribute";s:\d+:"(.*?)"~s',
				$serializedString, $matches)){
					foreach ($matches[1] as $offset => $attributeCode) {
						$attributes[] = $attributeCode;
					}
		}
        if (count($attributes)) {
        	/* @var $resource Mage_Core_Model_Resource */
        	$resource = Mage::getSingleton("core/resource");
        	$read = $resource->getConnection("core_read");
        	$write = $resource->getConnection("core_write");
        	$write->delete($resource->getTableName('cterms/agreement_product_attribute'), array('agreement_id=?' => $agreement->getId()));
        	
        	//Getting attribute IDs for attribute codes
        	$attributeIds = array();
        	$select = $read->select()
        	->from(array('a' => $resource->getTableName('eav/attribute')), array('a.attribute_id'))
        	->where('a.attribute_code IN (?)', array($attributes));
        	$attributesFound = $read->fetchAll($select);
        	if ($attributesFound) {
        		foreach ($attributesFound as $attribute) {
        			$attributeIds[] = $attribute['attribute_id'];
        		}

        		$data = array();
        		
        		foreach ($attributeIds as $attribute) {
        			$data[] = array (
        					'agreement_id'      => $agreement->getId(),
        					'attribute_id'      => $attribute
        					);
        		}
        		
        		$write->insertMultiple($resource->getTableName('cterms/agreement_product_attribute'), $data);
        	}
        }
	}
}