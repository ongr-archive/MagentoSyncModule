MagentoSyncModule
=================

Magento module for data sync and user login save to cookie.

Module saves data in cookies ``ongr_cart`` and ``ongr_user`` in json format.

``ongr_cart`` contains list of products in
cart where array key is a product id and value is the amount of products. Cookie is updated every time cart is saved or
user logs in.

``ongr_user`` contains information about user currently logged in. It is an associative array of customer properties.

Also this modules gives ability to update cart from anywhere.

Using module in ONGR project
----------------------------

Reaching saved cookies is easy when ONGR is on the same domain as magento store, they will be available in current
request. Domain of saved cookies can be configured in magento admin: system->configuration->ONGR SYNC->Sync Options.

Updating magento cart from ONGR can be achieved by sending any request to magento with
``OngrProducts`` parameter. ``OngrProducts`` should be array of products where key is product id and value quantity.
If ``OngrUrl`` parameter is provided module will redirect to that url after cart is updated.
