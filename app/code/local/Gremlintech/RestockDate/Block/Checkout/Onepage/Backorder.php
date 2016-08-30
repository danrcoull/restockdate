<?php

/**
 *
 * Class Gremlintech_RestockDate_Block_Checkout_Onepage_Backorder
 * @extends Mage_Core_Block_Template
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 *
 */
class Gremlintech_RestockDate_Block_Checkout_Onepage_Backorder extends Mage_Core_Block_Template
{


    /**
     * @description Used to get a collection of products from cart on backorder
     * @return Mage_Sales_Model_Resource_Quote_Item_Collection
     */
    public function getBackorderCollection()
    {
        //get current quote from session
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        //get all visible items from cart
        $cartItems = $quote->getAllVisibleItems();
        //create an id array
        $ids = array();
        //loop over and check if items on backorder
        foreach ($cartItems as $item) {
            if (!$item->getProduct()->load()->isInStock()
                && $item->getProduct()->load()->getRestockDate()
            ) {
                $ids[] = $item->getId();
            }
        }

        //get the resource model filte by selected item ids and quote
        $collection = Mage::getResourceModel('sales/quote_item_collection')
            ->setQuote($quote)// this is the model not an id.
            ->addFieldToFilter('item_id', array('in' => $ids));
        //return collection
        return $collection;
    }


}