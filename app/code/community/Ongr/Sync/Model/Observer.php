<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class Observer.
 */
class Ongr_Sync_Model_Observer
{
    /**
     * Parameter name for syncing with magento.
     */
    const CART_DATA_SYNC_PARAM_NAME = 'OngrProducts';

    /**
     * Parameter name for syncing with magento.
     */
    const CART_BACK_URL_PARAM_NAME = 'OngrUrl';

    /**
     * Action for controller_action_predispatch event.
     */
    public function controllerActionPredispatch()
    {
        /** @var Mage_Checkout_Model_Cart $cart */
        $cart = Mage::getSingleton('checkout/cart');

        $this->addProducts($cart);
    }

    /**
     * Action for checkout_cart_update_items_after event.
     *
     * @param Varien_Event_Observer $observer
     */
    public function checkoutCartSaveAfter(Varien_Event_Observer $observer)
    {
        /** @var Mage_Checkout_Model_Cart $cart */
        $cart = $observer->getData('cart');

        /** @var Ongr_Sync_Model_CookieSync $sync */
        $sync = Mage::getModel('ongr_Sync/CookieSync');
        $sync->syncProducts($this->getQuoteProducts($cart->getQuote()));
    }

    /**
     * Action for customer_login event.
     *
     * @param Varien_Event_Observer $observer
     */
    public function customerLogin(Varien_Event_Observer $observer)
    {
        /** @var Mage_Customer_Model_Customer $customer */
        $customer = $observer->getData('customer');

        /** @var Ongr_Sync_Model_CookieSync $sync */
        $sync = Mage::getModel('ongr_Sync/CookieSync');
        $sync->syncCustomer($customer->getId());

        // User might had items in cart before logging out.
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote');
        $quote->loadByCustomer($customer);
        $sync->syncProducts($this->getQuoteProducts($quote));
    }

    /**
     * Adds products to cart.
     *
     * @param Mage_Checkout_Model_Cart $cart
     */
    private function addProducts(Mage_Checkout_Model_Cart $cart)
    {
        $products = Mage::app()->getRequest()->getParam(self::CART_DATA_SYNC_PARAM_NAME);

        if (is_array($products)) {
            $cart->truncate();
            $product = Mage::getModel('catalog/product');

            $errors = [];

            foreach ($products as $id => $quantity) {
                $product->load($id);
                try {
                    $cart->addProduct($product, $quantity);
                } catch (Exception $e) {
                    $errors[] = $id;
                }
            }

            $cart->save();

            $backUrl = Mage::app()->getRequest()->getParam(self::CART_BACK_URL_PARAM_NAME);
            if ($backUrl) {
                if ($errors) {
                    $backUrl .= '?' . http_build_query(['e' => $errors]);
                }
                Mage::app()->getResponse()->setRedirect($backUrl);
            }
        }
    }

    /**
     * Extracts product list from cart.
     *
     * @param Mage_Sales_Model_Quote $quote
     *
     * @return array
     */
    private function getQuoteProducts(Mage_Sales_Model_Quote $quote)
    {
        /** @var Mage_Sales_Model_Quote_Item[] $items */
        $items = $quote->getAllItems();

        $products = [];
        foreach ($items as $item) {
            $products[$item->getProductId()] = $item->getQty();
        }

        return $products;
    }
}
