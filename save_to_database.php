<?php
include 'connect_db.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array();
    $data = $_POST;
    $rawData = trim($data['items_after_integer']);
    $datas = explode(',', $rawData);
    //var_dump($datas);

    $table_name = 'orders';
    $result = insert_data_id($data, $table_name, $con);

    // print_array($datas);exit;

    for ($i = 0; $i < count($datas); $i++) {
        echo "Loop iteration: " . $i . "\n";

        $add_item['order_id'] = $result;
        $add_item['item_name'] = $datas;
        $add_item['item_weight'] = '0';
        $add_item['item_quantity'] = '0';
        $add_item['item_price'] = '0';
        var_dump($add_item);

        $table_name2 = 'customer';

        echo " Value in datas array: " . $datas[$i] . "\n";
        $result2 = insert_data($add_item, $table_name2, $con);
        // echo "Result of insert_data: " . $result2;

        // Add this line to echo the value inside the loop
    }
}


?>