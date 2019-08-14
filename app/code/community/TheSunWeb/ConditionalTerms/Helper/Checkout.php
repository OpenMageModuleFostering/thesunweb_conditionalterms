<?php
class TheSunWeb_ConditionalTerms_Helper_Checkout extends Mage_Checkout_Helper_Data
{
    public function getRequiredAgreementIds()
    {
        if (is_null($this->_agreements)) {
            if (!Mage::getStoreConfigFlag('checkout/options/enable_agreements')) {
                $this->_agreements = array();
            } else {
                $agreements = Mage::getModel('checkout/agreement')->getCollection()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->addFieldToFilter('is_active', 1);

                $address = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
                foreach($agreements as $agreement) {
                    if($agreement->getConditions()->validate($address)) {
                        $this->_agreements[] = $agreement->getId();
                    }
                }
            }
        }
        return $this->_agreements;
    }
}
