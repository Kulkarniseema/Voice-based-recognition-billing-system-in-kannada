<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">


</head>

<?php
include 'connect_db.php';
$cust_id = $_GET['id'];
$bill_id = $_GET['bill_id'];

$total_bill = GetRow("select * from bill where id = '$bill_id' and cust_id = '$cust_id' ", $con);
$final_items = GetAllA("select * from bill_items where bill_id = '$bill_id' and cust_id = '$cust_id' and item_quantity != 0 ", $con);

?>

<body>
    <style>
        table {
            border-collapse: collapse;
        }

        th {
            padding: 1%;
        }

        td {
            padding: 1%;
        }

        #printableArea {

            padding: 5% 15% 5% 15%;
        }

        a {
            text-decoration: none;
            color: #000;
        }

        .btns1 {
            padding: 0% 5% 8% 40%;
        }
    </style>

    <!-- <script>
        window.onload = function () {
            window.print();
        }
    </script> -->

    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
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
    <section>
        <div id="printableArea">
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
                                <td align="left">Bill No <?php echo $total_bill['id']; ?></td>
                                <td align="right">Date <?php echo date_ch($total_bill['date']) ?></td>
                            </tr>
                        </table>
                        <br><br>
                        <div>
                            Mr/Ms <?php echo $total_bill['cust_name']; ?> <br>
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
                <?php for ($i = 0; $i < count($final_items); $i++) { ?>
                    <tr style="align-items: center;">
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $final_items[$i]['item_name']; ?></td>
                        <td><?php echo $final_items[$i]['item_price']; ?></td>
                        <td><?php echo $final_items[$i]['item_quantity']; ?></td>
                        <td><?php echo $final_items[$i]['item_total']; ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th colspan="2">Total Amount</th>
                    <th><?php echo $total_bill['total']; ?></th>
                </tr>
                <tr>
                    <th colspan="5" align="right">CSKN Kirani Store <br>
                        <br><br><br>
                        Authorized Signature
                    </th>
                </tr>
            </table>
        </div>
    </section>
    <div class="btns1">
        <input type="button" class="button2 button1 " onclick="printDiv('printableArea')" value="Print" />
        <button class="button2 button1 "><a href="index.php">BACK</a></button>
    </div>
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
</body>

</html>