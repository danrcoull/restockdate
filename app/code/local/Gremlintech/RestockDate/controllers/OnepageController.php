<?php
/**
 * Class Gremlintech_RestockDate_OnepageController
 * @extends Mage_Checkout_OnepageController
 * @requires Mage/Checkout/controllers/OnepageController.php
 * @author Daniel Coull <ttechitsolutions@gmail.com>
 */
require_once "Mage/Checkout/controllers/OnepageController.php";
class Gremlintech_RestockDate_OnepageController extends Mage_Checkout_OnepageController{

    /**
     * ovvewrite the initial indexaction
     * Check if the cart has backorder that hasnt been confirmed
     * If true redirect to backorder page else run the parent controller indexaction
     */
    public function indexAction()
    {
        /**
         * 1. Check if session has a confirmed value
         * 2. or check if cart
         */

        if(Mage::helper('restockdate')->cartHasBackorder()
            && !Mage::getSingleton('core/session')->getConfirmBackorder()
        )
        {
            Mage::getSingleton('checkout/session')->addError("You must confirm all backorders before continuing");
            $this->_redirect('checkout/onepage/backorder');
            return;
        }else
        {
            parent::indexAction();
        }
    }

    /**
     * Load Layout for backorder page
     */
    public function backorderAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Confirm Your Backorders'));
        $this->renderLayout();
    }

    /**
     * backorder page form action
     */
    public function confirmbackorderAction()
    {
        // Check csrf against form key to make sure it hasnt been forced
        if(Mage::app()->getRequest()->getPost('form_key')
            != Mage::getSingleton('core/session')->getFormKey())
        {
            //if this fails just return to the same page
            return;
        }

        //set the session variable for backorder confirmed
        Mage::getSingleton('core/session')->setConfirmBackorder(true);
        //redirect back to onepage checkout
        $this->_redirect('checkout/onepage/index');
        return;

    }


}
