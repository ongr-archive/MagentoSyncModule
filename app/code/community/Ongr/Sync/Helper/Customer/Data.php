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
 * Customer Data Helper.
 */
class Ongr_Sync_Helper_Customer_Data extends Mage_Customer_Helper_Data
{
    const BACK_URL_PARAM_NAME = 'OngrUrl';

    /**
     * {@inheritdoc}
     */
    public function getLoginPostUrl()
    {
        $params = [];
        if ($this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)) {
            $params[self::REFERER_QUERY_PARAM_NAME] = $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME);
        }

        if ($this->_getRequest()->getParam(self::BACK_URL_PARAM_NAME)) {
            $params[self::BACK_URL_PARAM_NAME] = urlencode($this->_getRequest()->getParam(self::BACK_URL_PARAM_NAME));
        }

        return $this->_getUrl('customer/account/loginPost', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegisterUrl()
    {
        $params = [];

        if ($this->_getRequest()->getParam(self::BACK_URL_PARAM_NAME)) {
            $params[self::BACK_URL_PARAM_NAME] = urlencode($this->_getRequest()->getParam(self::BACK_URL_PARAM_NAME));
        }

        return $this->_getUrl('customer/account/create', $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegisterPostUrl()
    {
        $params = [];

        if ($this->_getRequest()->getParam(self::BACK_URL_PARAM_NAME)) {
            $params[self::BACK_URL_PARAM_NAME] = urlencode($this->_getRequest()->getParam(self::BACK_URL_PARAM_NAME));
        }

        return $this->_getUrl('customer/account/createpost', $params);
    }
}
