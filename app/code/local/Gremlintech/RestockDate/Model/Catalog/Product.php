<?php

/**
 * Class Gremlintech_RestockDate_Model_Catalog_Product
 * @extends Mage_Catalog_Model_Product
 * @author  Daniel Coull <ttechitsolutions@gmail.com>
 */
class Gremlintech_RestockDate_Model_Catalog_Product extends Mage_Catalog_Model_Product
{

    /**
     * override the initial isInStock Method to include qty check
     * @return bool
     */
    public function isInStock()
    {
        $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($this)->getQty();

        if ($stock > 0 &&
            $this->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED
        ) {
            return true;
        }

        return false;
    }

    public function getRestockDate()
    {
        if ($helper = Mage::helper('restockdate')->isEnabled()) {
            //switch on product type

            switch ($this->getTypeId()) {
                //if product type is simple
                case 'simple':
                    //get current stock level

                    //if can not be sold or no quantity
                    if (!$this->isInStock()) {
                        //if product restock date return it
                        if ($date = $this->getData('restock_date')) {

                            $date = date('d-m-Y', strtotime($date));
                            if (strtotime($date) > time()) {
                                return $date;
                            } else {
                                $debug = "Product Id " . $this->getId() . " in stock date has passed please update.";
                                Mage::helper('restockdate')->isDebug($debug);
                            }
                        }
                    }
                    break;
            }
        }

        return false;
    }
}
		