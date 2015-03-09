<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// We need to add this one since Magento wont recognize it automatically.
require_once Mage::getModuleDir('controllers', 'Mage_Customer') . DS . 'AccountController.php';

/**
 * Customer account controller.
 */
class Ongr_Sync_Frontend_Customer_AccountController extends Mage_Customer_AccountController
{
    /**
     * Parameter name for syncing with magento.
     */
    const BACK_URL_PARAM_NAME = 'OngrUrl';

    /**
     * {@inheritdoc}
     */
    protected function _loginPostRedirect()
    {
        $backUrl = $this->getRequest()->getParam(self::BACK_URL_PARAM_NAME);

        if ($backUrl) {
            $this->_redirectUrl($backUrl);
        } else {
            parent::_loginPostRedirect();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _successProcessRegistration(Mage_Customer_Model_Customer $customer)
    {
        parent::_successProcessRegistration($customer);

        if ($this->_getSession()->isLoggedIn()) {
            $backUrl = $this->getRequest()->getParam(self::BACK_URL_PARAM_NAME);
            if ($backUrl) {
                $this->getResponse()->setRedirect($backUrl);

                return;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function logoutAction()
    {
        $this->_getSession()->logout()->renewSession();

        $backUrl = $this->getRequest()->getParam(self::BACK_URL_PARAM_NAME);

        if ($backUrl) {
            $this->_redirectUrl($backUrl);
        } else {
            $this->_redirect('*/*/logoutSuccess');
        }
    }
}
