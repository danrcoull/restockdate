<?php

/**
 * Class Gremlintech_RestockDate_Helper_Data
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */
class Gremlintech_RestockDate_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_ENABLED = 'restockdate/general/enabled';
    const XML_DEBUG = 'restockdate/general/debug';

    /**
     * @method isEnabled
     * @description  Returns if the module is enabled from system config.
     * @return bool  returns True | False
     */
    public function isEnabled()
    {
        if(Mage::getStoreConfig(self::XML_ENABLED))
        {
            return true;
        }

        return false;
    }

    /**
     * @method isDebug
     * @param $string message returned for the log file
     * @return $this this returned for chaining
     */
    public function isDebug($string)
    {
        if(Mage::getStoreConfig(self::XML_DEBUG))
        {
            Mage::log($string , null, 'restockdate.log', true);
        }

        return $this;
    }

    public function cartHasBackorder()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cartItems = $quote->getAllVisibleItems();
        foreach ($cartItems as $item) {
            if(!$item->getProduct()->load()->isInStock()
                && $item->getProduct()->load()->getRestockDate())
            {
                return true;
            }
        }

        return false;
    }
}
	 