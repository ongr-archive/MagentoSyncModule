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
 * Class CookieSync.
 */
class Ongr_Sync_Model_CookieSync
{
    /**
     * Name of the cookie where cart data is saved.
     */
    const CART_DATA_COOKIE_NAME = 'ongr_cart';

    /**
     * Name of the cookie where cart data is saved.
     */
    const USER_DATA_COOKIE_NAME = 'ongr_user';

    /**
     * Length of life of cart cookie.
     */
    const COOKIE_LIFETIME = 86400;


    /**
     * Saves given products to cookie.
     *
     * @param array $products
     *
     * @return bool
     */
    public function syncProducts($products)
    {
        return setcookie(
            self::CART_DATA_COOKIE_NAME,
            json_encode($products),
            self::COOKIE_LIFETIME ? time() + self::COOKIE_LIFETIME : 0,
            '/',
            '',
            false,
            false
        );
    }

    /**
     * Saves customer data to cookie.
     *
     * @param int   $id
     * @param array $data
     *
     * @return bool
     */
    public function syncCustomer($id, array $data = [])
    {
        $data['id'] = $id;

        return setcookie(
            self::USER_DATA_COOKIE_NAME,
            json_encode(['id' => $id, 'data' => $data]),
            self::COOKIE_LIFETIME ? time() + self::COOKIE_LIFETIME : 0,
            '/',
            '',
            false,
            false
        );
    }
}
