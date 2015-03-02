MagentoSyncModule
=================

Magento module for data sync and user login save to cookie.

Module saves date cookies ``ongr_cart`` and ``ongr_user`` in json format. ``ongr_cart`` contains list of products in
cart where array key is product id and value amount of products. Cookie is updated every time cart is saved or user logs
in. ``ongr_user`` contains information about currently logged in user in object with ``id`` and ``data`` where id is
customer id and data is associative array of customer properties.

Also this modules gives ability to update cart from outside.

Using module in ONGR project
----------------------------

Reaching saved cookies is easy when ONGR is on the same domain as magento store, they will be available in current
request. Domain of saved cookies can be configured in magento admin: system->configuration->ONGR SYNC->Sync Options.

Updating magento cart from ongr can be achieved by sending request (either GET or POST) to magento with
``OngrProducts`` parameter in following format. ``OngrProducts`` should be array of products and each product should
have ``id`` and ``qty`` key which stores product id and quantity respectively.
