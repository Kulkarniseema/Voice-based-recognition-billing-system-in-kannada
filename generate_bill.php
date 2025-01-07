<?php
include 'connect_db.php';
$cust_id = $_GET['id'];
$order_id = $_GET['order_id'];
$order = GetAllA("SELECT * FROM customer WHERE cust_id = '$cust_id' AND order_id = '$order_id' ", $con);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate bill</title>
    <link rel="stylesheet" href="style.css">

</head>
<style>
    h1 {
        text-align: center;
    }
</style>

<body>
    <div class="header">
        <button class="js-menu-show header__menu-toggle material-icons"><svg width='30' height='30'>
                <path d='M0,5 30,5' stroke='#000' stroke-width='5' />
                <path d='M0,14 30,14' stroke='#000' stroke-width='5' />
                <path d='M0,23 30,23' stroke='#000' stroke-width='5' />
            </svg></button>
    </div>


    <aside class="js-side-nav side-nav">
        <nav class="js-side-nav-container side-nav__container">
            <button class="js-menu-hide side-nav__hide material-icons">close</button>

            <header class="side-nav__header">Billing system</header>
            <ul class="side-nav__content">
                <a href="index.php">
                    <li>Home</li>
                </a>
                <a href="customer_list.php">
                    <li>Customer List</li>
                </a>
                <a href="items_list.php">
                    <li>Items List</li>
                </a>

            </ul>
        </nav>
    </aside>
    <h1>Verify Your Order</h1>
    <form action="generate_bill.php" method="post" enctype="multipart/form-data">
        <section class="order">


            <div class="right">

                <section>
                    <!--for demo wrap-->

                    <div class="tbl-header">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th>SL.NO</th>
                                    <th>Items</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th> Remove</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tbl-content">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                                <tr>
                                    <!-- <td>1</td>
                                <td>ಉದ್ದಿನಬೇಳೆ</td>
                                <td>2</td>
                                <td>20</td>
                                <td>20</td>
                                <td><button class="button3 button_round">-</button></td> -->

                                    <?php
                                    $total = 0;
                                    for ($i = 0; $i < count($order); $i++) {
                                        $item_total = $order[$i]['item_price'] * $order[$i]['item_quantity'];
                                        $total += $item_total;
                                    ?>
                                <tr style="text-align: center;" id="rownum<?php echo ($i); ?>">
                                    <td style="border-left: 1px solid #000;"><?php echo ($i + 1); ?></td>
                                    <style>
                                        .no-border {
                                            border: none;
                                            box-shadow: none;
                                        }
                                    </style>
                                    <td>
                                        <input name="item_name<?php echo ($i); ?>" type="text" value="<?php echo ($order[$i]['item_name']); ?>" required readonly class="no-border">
                                    </td>
                                    <td>
                                        <input name="item_quantity<?php echo ($i); ?>" id="item_quantity<?php echo ($i); ?>" type="number" value="<?php echo ($order[$i]['item_quantity']); ?>" required class="no-border" onchange="updateTotal()">
                                    </td>
                                    <td>
                                        <input name="item_price<?php echo ($i); ?>" id="item_price<?php echo ($i); ?>" type="text" value="<?php echo ($order[$i]['item_price']); ?>" required readonly class="no-border">
                                    </td>
                                    <td>
                                        <input name="item_total<?php echo ($i); ?>" id="item_total<?php echo ($i); ?>" type="text" value="<?php echo ($item_total); ?>" required readonly class="no-border">
                                    </td>
                                    <td>
                                        <a href="#" class="button3 button_round" onclick="return removeItem(<?php echo ($i); ?>)">-</a>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <td colspan="3"> </td>

                                <td>Total</td>

                                <td><input name="total" id="total" type="text" value="<?php echo $total; ?>" required readonly class="no-border"></td>


                            </tr>

                            </tbody>
                        </table>

                    </div>

                </section>

                <input type="hidden" name="cust_id" value="<?php echo $cust_id; ?>">
                <input type="submit" name="submit" value="Submit" class="button2 button1 btn-align" />
                <!-- <button class="button2 button1 btn-align">Submit</button> -->
                
        </section>
        <?php
        if (isset($_POST['submit'])) {

            $data = array();
            $data = $_POST;
            unset($data['submit']);
            $cust_id = $data['cust_id'];

            $cust_name = GetOne("SELECT name FROM add_customer WHERE id = '$cust_id' ", $con);


            $bill_data = array();
            $bill_data['cust_id'] = $data['cust_id'];
            $bill_data['cust_name'] = $cust_name;
            $bill_data['total'] = $data['total'];
            $bill_data['date'] = date('Y-m-d H:i:s');


            $table_name = 'bill';
            $result = insert_data_id($bill_data, $table_name, $con);


            $item_count = count(array_filter(array_keys($data), function ($key) {
                return strpos($key, 'item_name') !== false;
            }));

            for ($i = 0; $i < $item_count; $i++) {
                $bill_item_data = array();
                $bill_item_data['cust_id'] = $data['cust_id'];
                $bill_item_data['bill_id'] = $result;
                $bill_item_data['item_name'] = $data['item_name' . $i];
                $bill_item_data['item_quantity'] = $data['item_quantity' . $i];
                $bill_item_data['item_price'] = $data['item_price' . $i];
                $bill_item_data['item_total'] = $data['item_total' . $i];
                $bill_item_data['date_h'] = date('Y-m-d H:i:s');


                $table_name = 'bill_items';
                $inserted = insert_data($bill_item_data, $table_name, $con);
            }

            echo "<script>window.location.href='print_bill.php?id=$cust_id&bill_id=$result';</script>";
        }

        ?>
    </form>

</body>
<script>
    (() => {
        const factorySideNav = function factorySideNav() {
            // DOM
            const showButtonEl = document.querySelector('.js-menu-show');
            const hideButtonEl = document.querySelector('.js-menu-hide');
            const sideNavEl = document.querySelector('.js-side-nav');
            const sideNavContainerEl = document.querySelector('.js-side-nav-container');

            // State
            let startX = 0;
            let currentX = 0;
            let touchingSideNav = false;

            const onTransitionEnd = function onTransitionEnd(evt) {
                sideNavEl.classList.remove('side-nav--animatable');
                sideNavEl.removeEventListener('transitionend', onTransitionEnd);
            };

            const showSideNav = function showSideNav() {
                sideNavEl.classList.add('side-nav--animatable');
                sideNavEl.classList.add('side-nav--visible');
                sideNavEl.addEventListener('transitionend', onTransitionEnd);
            };

            const hideSideNav = function hideSideNav() {
                sideNavEl.classList.add('side-nav--animatable');
                sideNavEl.classList.remove('side-nav--visible');
                sideNavEl.addEventListener('transitionend', onTransitionEnd);
            };

            const update = function update() {
                if (!touchingSideNav) return;

                requestAnimationFrame(update);

                const translateX = Math.min(0, currentX - startX);
                sideNavContainerEl.style.transform = `translateX( ${translateX}px )`;
            };

            const onTouchStart = function onTouchStart(evt) {
                if (!sideNavEl.classList.contains('side-nav--visible')) return;

                startX = evt.touches[0].pageX;
                currentX = startX;

                touchingSideNav = true;
                requestAnimationFrame(update);
            };

            const onTouchMove = function onTouchMove(evt) {
                if (!touchingSideNav) return;

                currentX = evt.touches[0].pageX;
            };

            const onTouchEnd = function onTouchEnd(evt) {
                if (!touchingSideNav) return;

                touchingSideNav = false;

                const translateX = Math.min(0, currentX - startX);
                sideNavContainerEl.style.transform = '';

                if (translateX < 0) hideSideNav();
            }

            const blockClicks = function blockClicks(evt) {
                evt.stopPropagation();
            };

            const addEventListeners = function addEventListeners() {
                showButtonEl.addEventListener('click', showSideNav);
                hideButtonEl.addEventListener('click', hideSideNav);
                sideNavEl.addEventListener('click', hideSideNav);
                sideNavContainerEl.addEventListener('click', blockClicks);

                sideNavEl.addEventListener('touchstart', onTouchStart);
                sideNavEl.addEventListener('touchmove', onTouchMove);
                sideNavEl.addEventListener('touchend', onTouchEnd);
            };

            return {
                addEventListeners,
            };
        }

        const sideNav = factorySideNav();

        sideNav.addEventListeners();
    })()
</script>
<script>
    $(window).on("load resize ", function() {
        var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
        $('.tbl-header').css({
            'padding-right': scrollWidth
        });
    }).resize();
</script>
<script>
    function updateTotal() {
        var cnt = <?php echo count($order); ?>;

        var full_total = 0;

        for (var i = 0; i < cnt; i++) {
            var quantity = document.getElementById('item_quantity' + i).value;
            var price = document.getElementById('item_price' + i).value;
            var total = quantity * price;
            document.getElementById('item_total' + i).value = total;

            full_total += total;
        }

        document.getElementById('total').value = full_total;
    }

    function removeItem(m) {

        document.getElementById('item_quantity' + m).value = 0;
        document.getElementById('item_price' + m).value = 0;
        document.getElementById('item_total' + m).value = 0;

        updateTotal();

        var row = document.getElementById('rownum' + m);
        row.style.display = 'none';

        return false;
    }
</script>

</html>