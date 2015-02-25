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
        /** @var Mage_Sales_Model_Quote_Item[] $items */
        $items = $cart->getQuote()->getAllItems();

        $products = [];
        foreach ($items as $item) {
            $products[$item->getProductId()] = $item->getQty();
        }

        /** @var Ongr_Sync_Model_CookieSync $sync */
        $sync = Mage::getModel('ongr_Sync/CookieSync');
        $sync->syncProducts($products);
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
    }

    /**
     * Adds products to cart.
     *
     * @param Mage_Checkout_Model_Cart $cart
     */
    private function addProducts(Mage_Checkout_Model_Cart $cart)
    {
        $data = Mage::app()->getRequest()->getParam('OngrProducts');

        if (is_array($data)) {
            $cart->truncate();
            $product = Mage::getModel('catalog/product');

            foreach ($data as $productData) {
                $product->load($productData['id']);
                $cart->addProduct($product, $productData['qty']);
            }

            $cart->save();
        }
    }
}
