# Pickup locations for CS-Cart 4.1.x 

To make this plugin work:
1. open app/Tygh/Shippings/Shippings.php
2. find function getShippingsList
3. find variable $shippings_info
4. add line "?:shippings.bd_pickup_provider, " to the SELECT clause