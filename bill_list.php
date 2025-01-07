<?php
include 'connect_db.php';

$customer_id = $_GET['id'];

$bills = GetAllA("SELECT * FROM bill where cust_id = $customer_id and date != 0 ", $con);

// print_array($bills);


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>take order</title>
    <link rel="stylesheet" href="style.css">

</head>
<style>
    h1 {
        text-align: center;
        font-size: xx-large;
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
    <section class="itemlist">

        <h1>Bill List</h1>
        <section>
            <!--for demo wrap-->

            <div class="tbl-header">
                <table cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>SL.NO</th>
                            <th>Date</th>
                            <th>View</th>


                        </tr>
                    </thead>
                </table>
            </div>
            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <?php
                        $processedDates = array(); // Array to store processed dates
                        $rowNum = 0;
                        for ($i = 0; $i < count($bills); $i++) {
                            $currentDate = $bills[$i]['date'];

                            // Check if the current date has already been processed
                            if (!in_array($currentDate, $processedDates)) {
                                $processedDates[] = $currentDate; // Add the current date to the processed list
                                $rowNum++;
                        ?>
                                <tr>
                                    <td><?php echo $rowNum; ?></td>
                                    <td><?php echo date_ch($currentDate); ?></td>
                                    <td> <a href="bills.php?date=<?php echo ($currentDate); ?>&id=<?php echo $customer_id; ?>">View</a></td>


                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </section>



    </section>

    </div>
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

</html>