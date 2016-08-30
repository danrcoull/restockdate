<?php

/**
 * Class Gremlintech_Restockdate_Model_Observer
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */
class Gremlintech_Restockdate_Model_Observer
{
    public function catalog_product_save_after($observer)
    {
        //get the restockdarte Data helper
        $helper = Mage::helper('restockdate');

        //only run if module is set to enabled
        if ($helper->isEnabled()) {

            //get product and id from observer
            $product = $observer->getProduct();
            $id = $product->getId();

            //Basising this on normal input of 7am-10pm but should be 07:00-16:00
            //this will never be 100% for all sites but should work for most.
            //
            $store_hours = str_ireplace(array('am', 'pm'), '', Mage::getStoreConfig('general/store_information/hours'));
            $start_finish = explode('-', $store_hours);
            //get current time
            $now = time();

            //check start and finish times exist
            if (count($start_finish) == 2) {

                //check if the times meet the criterias
                if ($now < strtotime($start_finish[0]) || $now > strtotime($start_finish[1])) {
                    //run debug to show observer running
                    $debug = $helper->__('Debug:: Product Saved out of hours Id:') . $id;
                    $helper->isDebug($debug);


                    //load the locale template from xml
                    $emailTemplate = Mage::getModel('core/email_template')
                        ->loadDefault('out_of_hours_template_1');

                    //Create an array of variables to assign to template
                    $emailTemplateVariables = array();
                    $emailTemplateVariables['productId'] = $id;
                    $emailTemplateVariables['time'] = date('d-m-Y H::m', time());
                    $emailTemplateVariables['admin'] = Mage::getSingleton('admin/session')->getUser()->getUsername();


                    /*
                     * note getProcessedTemplate is called inside send()
                     */
                    $emailTo = Mage::getStoreConfig('trans_email/ident_general/email');
                    $emailName = Mage::getStoreConfig('trans_email/ident_general/name');
                    $emailTemplate->send($emailTo, $emailName, $emailTemplateVariables);

                }

            } else {

                $debug = $helper->__('Error:: Opening Hours wrong format should be 7:00-18:00');
                $helper->isDebug($debug);
            }

            //peace of mind return the class instance
            return $this;

        }

    }
}