<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

/** HOOKS **/

function fn_bd_pickup_providers_shippings_get_shippings_list_conditions($group, $shippings, &$fields, $join, $condition, $order_by) {
    $fields[] = "?:shippings.bd_pickup_provider";
}


function fn_bd_pickup_providers_create_order(&$order) {
    if(isset(Tygh::$app['session']['bd_pickup_provider']) && Tygh::$app['session']['bd_pickup_provider']['shipping_id'] == $order['shipping_ids']) {
        $order['bd_pickup_provider_data'] = Tygh::$app['session']['bd_pickup_provider']['data'];
    }
}

/** BUSINESS LOGIC **/

function fn_bd_pickup_providers_list_options_for($provider) {
    switch ($provider) {
        case 'smartpost_fi':
            $select_body = fn_bd_pickup_providers_smartpost_fi();
            break;
        case 'smartpost_ee':
            $select_body = fn_bd_pickup_providers_smartpost_ee();
            break;
        case 'omniva_ee':
            $select_body = fn_bd_pickup_providers_omniva('EE', 'A2_NAME', 'Tallinn');
            break;
        case 'omniva_lv':
            $select_body = fn_bd_pickup_providers_omniva('LV', 'A1_NAME', 'Rīga');
            break;
        case 'omniva_lt':
            $select_body = fn_bd_pickup_providers_omniva('LT', 'A1_NAME', 'Vilnius');
            break;
        case 'dpd_ee':
            $select_body = fn_bd_pickup_providers_dpd('EE', 'Tallinn');
            break;
        case 'dpd_lv':
            $select_body = fn_bd_pickup_providers_dpd('LV', 'Riga');
            break;
        case 'dpd_lt':
            $select_body = fn_bd_pickup_providers_dpd('LT', 'Vilnius');
            break;
        case 'cargobus_ee':
            $select_body = fn_bd_pickup_providers_cargobus_ee();
            break;
               
    }
    return $select_body;
}

function fn_bd_pickup_providers_cargobus_ee() {
    $body = '';
    $optgroup = array();
    $groupkey = 'country';
    $firstgroup = 'Eesti';
    $firstoptgroup_html = '';

    $csv = fn_bd_pickup_providers_csv2array(__DIR__ . '/' . BD_PICKUP_PROVIDERS_CARGOBUS_URL, 'str_getcsv');
    $csv = fn_bd_pickup_providers_order_by($groupkey, $csv);
    $csv[] = array($groupkey => null); //adding fake group to the end, it is a hack to display the real last group
    $group = $csv[0][$groupkey];

    foreach ($csv as $key => $dest) {
        if($group != $dest[$groupkey]) {
            sort($optgroup); //sort by option visible text
            $optgroup_html = '<optgroup label="'.$group.'">'.implode($optgroup).'</optgroup>';
            if($group == $firstgroup) {
                $firstoptgroup_html = $optgroup_html;
            } else {
                $body .= $optgroup_html;
            }
            $optgroup = array();
            $group = $dest[$groupkey];
        } else {
            $group = $dest[$groupkey];
        }

        $optgroup[] = fn_bd_pickup_providers_build_select_option_text($dest['id'], $dest['city'].', '.$dest['address']);
    }

    $body = $firstoptgroup_html.$body;
    return $body;
}

function fn_bd_pickup_providers_dpd($country, $firstgroup) {
    //'PUDO ID' = EE90 -> automaat; EE91 -> robot; EE10 -> pakipood;

    $body = '';
    $optgroup = array();
    $groupkey = 'ORT';
    $firstgroup = $firstgroup;
    $firstoptgroup_html = '';

    $csv = fn_bd_pickup_providers_csv2array(BD_PICKUP_PROVIDERS_DPD_URL, 'fn_bd_pickup_providers_getcsv');
    $csv = fn_bd_pickup_providers_order_by($groupkey, $csv);
    $csv[] = array($groupkey => null, 'PUDO ID' => $country); //adding fake group to the end, it is a hack to display the real last group    
    $group = $csv[0][$groupkey];

    foreach ($csv as $key => $dest) {
        if(stristr($dest['PUDO ID'], $country) !== FALSE) {
            if($group != $dest[$groupkey] && count($optgroup) > 0) {
                sort($optgroup); //soft by option visible text
                $optgroup_html = '<optgroup label="'.$group.'">'.implode($optgroup).'</optgroup>';
                if($group == $firstgroup) {
                   $firstoptgroup_html = $optgroup_html;
                } else {
                    $body .= $optgroup_html;
                }
                $optgroup = array();
                $group = $dest[$groupkey];
            } else {
                $group = $dest[$groupkey];
            }

            $optgroup[] = fn_bd_pickup_providers_build_select_option_text($dest['ID_PAKETSHOP'], $dest['Firma'], $dest['ORT'], $dest['Straße Hausnummer'], $dest['PLZ']);
        }
    }

    $body = $firstoptgroup_html.$body;
    return $body;
    
}

function fn_bd_pickup_providers_omniva($country, $groupkey, $firstgroup) {
    $json = json_decode(file_get_contents(BD_PICKUP_PROVIDERS_OMNIVA_URL), true);

    $body = '';
    $address = '';
    $optgroup = array();
    $firstgroup = $firstgroup;
    $firstoptgroup_html = '';

    $json = fn_bd_pickup_providers_order_by($groupkey, $json);
    $json[] = array($groupkey => null, 'A0_NAME' => $country, 'TYPE' => '0'); //adding fake group to the end, it is a hack to display the real last group
    $group = $json[0][$groupkey];

    foreach ($json as $key => $dest) {

        if($dest['A0_NAME'] == $country && $dest['TYPE'] == '0') {
            $is_null = function($item) {
                return ($item == 'NULL' ? '' : $item);
            };
            $dest = array_map($is_null, $dest);
            
            if($group != $dest[$groupkey] && count($optgroup) > 0) {
                sort($optgroup); //soft by option visible text
                $optgroup_html = '<optgroup label="'.$group.'">'.implode($optgroup).'</optgroup>';
                if($group == $firstgroup) {
                   $firstoptgroup_html = $optgroup_html;
                } else {
                    $body .= $optgroup_html;
                }
                $optgroup = array();
                $group = $dest[$groupkey];
            } else {
                $group = $dest[$groupkey];
            }

            if ($country == 'EE') {
                $address = (!empty($dest['A7_NAME']) ? $dest['A5_NAME'].' '.$dest['A7_NAME'] : $dest['A5_NAME']);
            } else {
                $address = $dest['A2_NAME'];
            }

            $optgroup[] = fn_bd_pickup_providers_build_select_option_text($dest['ZIP'], $dest['NAME'], $dest['A3_NAME'], $address);
        }
    }

    $body = $firstoptgroup_html.$body;
    return $body;
}

function fn_bd_pickup_providers_smartpost_ee() {
    $body = '';
    $optgroup = array();
    $groupkey = 'group_name';
    $firstgroup = 'Tallinn/Harjumaa';
    $firstoptgroup_html = '';

    $csv = fn_bd_pickup_providers_csv2array(BD_PICKUP_PROVIDERS_SMARTPOST_EE_URL, 'str_getcsv');
    $csv = fn_bd_pickup_providers_order_by($groupkey, $csv);
    $csv[] = array($groupkey => null); //adding fake group to the end, it is a hack to display the real last group
    $group = $csv[0][$groupkey];

    foreach ($csv as $key => $dest) {
        if($group != $dest[$groupkey]) {
            sort($optgroup); //sort by option visible text
            $optgroup_html = '<optgroup label="'.$group.'">'.implode($optgroup).'</optgroup>';
            if($group == $firstgroup) {
                $firstoptgroup_html = $optgroup_html;
            } else {
                $body .= $optgroup_html;
            }
            $optgroup = array();
            $group = $dest[$groupkey];
        } else {
            $group = $dest[$groupkey];
        }

        if((int)$dest['active'] === 1) {
            $optgroup[] = fn_bd_pickup_providers_build_select_option_text($dest['place_id'], $dest['name'], $dest['city'], $dest['address']);
        }
    }

    $body = $firstoptgroup_html.$body;
    return $body;
}

function fn_bd_pickup_providers_smartpost_fi() {
    $json = json_decode(file_get_contents(BD_PICKUP_PROVIDERS_SMARTPOST_FI_URL), true);

    $body = '';
    $optgroup = array();
    $groupkey = 'city';
    $firstgroup = 'HELSINKI';
    $firstoptgroup_html = '';

    $json = fn_bd_pickup_providers_order_by($groupkey, $json);
    $json[] = array($groupkey => null); //adding fake group to the end, it is a hack to display the real last group
    $group = $json[0][$groupkey];

    foreach ($json as $key => $dest) {
        $dest['postoffice'] = str_replace('Posti Parcel Locker, ', '', $dest['postoffice']);
        
        if($group != $dest[$groupkey] && count($optgroup) > 0) {
            sort($optgroup); //soft by postoffice, that is option visible text
            $optgroup_html = '<optgroup label="'.$group.'">'.implode($optgroup).'</optgroup>';
            if($group == $firstgroup) {
                $firstoptgroup_html = $optgroup_html;
            } else {
                $body .= $optgroup_html;
            }
            $optgroup = array();
            $group = $dest[$groupkey];
        } else {
            $group = $dest[$groupkey];
        }

        $optgroup[] = fn_bd_pickup_providers_build_select_option_text($dest['code'], $dest['postoffice'], $dest['city'], $dest['address']);
    }

    $body = $firstoptgroup_html.$body;
    return $body;
}

function fn_bd_pickup_providers_build_select_option_text($id, $place, $city='', $address='', $zip='') {
    $id = filter_var($id, FILTER_SANITIZE_STRING);
    $place = filter_var($place, FILTER_SANITIZE_STRING);
    $city = filter_var($city, FILTER_SANITIZE_STRING);
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $zip = filter_var($zip, FILTER_SANITIZE_STRING);

    $parts = array();

    if($address) $parts[] = trim($address);
    if($zip) $parts[] = trim($zip);
    if($city) $parts[] = trim($city);

    if(count($parts)) {
        $brackets = ' ('.implode(', ', $parts).')';
    } else {
        $brackets = '';
    }

    $option_text = $place.$brackets;

    $v = json_encode(array('location' => $option_text, 'code' => $id), JSON_UNESCAPED_UNICODE);
    if(isset(Tygh::$app['session']['bd_pickup_provider']) && Tygh::$app['session']['bd_pickup_provider']['data'] == $v) {
        $sel = 'selected="selected"';
    } else {
        $sel = '';
    }

    return "<option value='$v' $sel>$option_text</option>";
}

/** HELPERS **/

function fn_bd_pickup_providers_csv2array($url, $callback) {
    $csv = array_map($callback, file($url));
    array_walk($csv, function(&$tmp) use ($csv) {
      $tmp = array_combine($csv[0], $tmp);
    });
    array_shift($csv); # remove column header
    return $csv;
}

function fn_bd_pickup_providers_order_by($field, $data) {
    usort($data, function ($a, $b) use ($field) { return strnatcmp($a[$field], $b[$field]); } );
    return $data;
}

function fn_bd_pickup_providers_getcsv($s) {
    return str_getcsv(utf8_encode($s), '|'); //for DPD only that uses ISO endiding and custom delimiter
}