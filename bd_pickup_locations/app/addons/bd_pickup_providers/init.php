<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'shippings_get_shippings_list_conditions',
    'create_order'
);