<?php
include 'connect_db.php';

$date = $_GET['date'];
$customer_id = $_GET['id'];

$cust_name = GetOne("SELECT name FROM add_customer WHERE id = '$customer_id' ", $con);


$bill = GetAllA("SELECT * FROM bill_items where cust_id = $customer_id and date_h = '$date' ", $con);

?>

<table border="2" width="60%" align="Center">
    <tr>
        <th colspan="5" style="padding: 2%;">
            <div align="right">
                Contact= +91-1234567891 <br> +91-958698565
            </div>
            <div align="center" style="font-size: 1.5em; font-family: 'calibri'"> <b>CSKN Kirani Store</b> </div>
            <div align="center"> Deals and Offers all Kirani items etc. <br>
                Address: KC rano road gadag-582101
            </div>
            <br><br>
            <table border="0" width="100%">
                <tr>
                    <td align="left">Bill No: <?php echo $customer_id ?></td>
                    <td align="right">Date: <?php echo date_ch($date) ?></td>
                </tr>
            </table>
            <br><br>
            <div>
                Mr/Ms <?php echo $cust_name; ?> <br>
            </div>
        </th>
    </tr>
    <tr>
        <th width="10%">S.No</th>
        <th width="50%">Item Name</th>
        <th> Rate </th>
        <th> Qty. </th>
        <th width="20%">Amount
            <br>Rs. P.
        </th>
    </tr>
    <?php for ($i = 0; $i < count($bill); $i++) { ?>
        <tr style="align-items: center;">
            <td><?php echo $i + 1; ?></td>
            <td><?php echo $bill[$i]['item_name']; ?></td>
            <td><?php echo $bill[$i]['item_price']; ?></td>
            <td><?php echo $bill[$i]['item_quantity']; ?></td>
            <td><?php echo $bill[$i]['item_total']; ?></td>
        </tr>
    <?php } ?>
    <!-- <tr height="500">
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr> -->
    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th colspan="2">Total Amount</th>
        <?php
        $total = 0;
        for ($i = 0; $i < count($bill); $i++) {
            $total += $bill[$i]['item_total'];
        }
        ?>
        <th><?php echo $total; ?></th>
    </tr>
    <tr>
        <th colspan="5" align="right">CSKN Kirani Store <br>
            <br><br><br>
            Authorized Signature
        </th>
    </tr>
</table>