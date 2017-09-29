<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('sales/quote_payment')}` 
    ADD `installment` TINYINT UNSIGNED NOT NULL DEFAULT '1',
    ADD `extra_installment`  VARCHAR( 10 ) NOT NULL DEFAULT '',
    ADD `use3d_secure` TINYINT UNSIGNED NOT NULL DEFAULT '1',
    ADD `bank_id` VARCHAR( 60 ) NOT NULL,
    ADD `gateway` VARCHAR( 30 ) NOT NULL
;
  
ALTER TABLE `{$this->getTable('sales/order_payment')}`
    ADD `installment` TINYINT UNSIGNED NOT NULL DEFAULT '1',
    ADD `extra_installment`  VARCHAR( 10 ) NOT NULL DEFAULT '',
    ADD `use3d_secure` TINYINT UNSIGNED NOT NULL DEFAULT '1',
    ADD `bank_id` VARCHAR( 60 ) NOT NULL,
    ADD `gateway` VARCHAR( 30 ) NOT NULL
;

ALTER TABLE  `{$this->getTable('sales/quote_address')}`
ADD  `commission_amount` DECIMAL( 10, 2 ) NOT NULL;


ALTER TABLE  `{$this->getTable('sales/quote_address')}`
ADD  `base_commission_amount` DECIMAL( 10, 2 ) NOT NULL;


ALTER TABLE  `{$this->getTable('sales/order')}`
ADD  `commission_amount` DECIMAL( 10, 2 ) NOT NULL;

ALTER TABLE  `{$this->getTable('sales/order')}`
ADD  `base_commission_amount` DECIMAL( 10, 2 ) NOT NULL;

SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 