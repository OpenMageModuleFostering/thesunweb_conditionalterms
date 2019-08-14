<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS `{$installer->getTable('cterms/agreement_product_attribute')}`;
CREATE TABLE `{$installer->getTable('cterms/agreement_product_attribute')}` (
  `agreement_id` int(10) unsigned NOT NULL,
  `attribute_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`agreement_id`,`attribute_id`),
  KEY `IDX_ATTRIBUTE` (`attribute_id`),
  CONSTRAINT `FK_AGREEMENT_PRODUCT_ATTRIBUTE_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav/attribute')}` (`attribute_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_AGREEMENT_PRODUCT_ATTRIBUTE_AGREEMENT` FOREIGN KEY (`agreement_id`) REFERENCES `{$this->getTable('checkout/agreement')}` (`agreement_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
