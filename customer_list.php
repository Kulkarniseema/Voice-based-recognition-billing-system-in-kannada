<?php
include 'connect_db.php';

$customers = GetAllA("SELECT * FROM add_customer where del=0 ", $con);



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

        <h1>Coustomer List</h1>
        <section>
            <!--for demo wrap-->

            <div class="tbl-header">
                <table cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th>SL.NO</th>
                            <th>Name</th>
                            <th>Bill</th>


                        </tr>
                    </thead>
                </table>
            </div>
            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($customers); $i++) { ?>
                            <tr>
                                <td><?php echo ($i + 1); ?></td>
                                <td><?php echo ($customers[$i]['name']); ?></td>
                                <td><a href="bill_list.php?id=<?php echo ($customers[$i]['id']); ?>">View</a></td>


                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>



    </section>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="floating-container">
        <a href="index.php">
            <div class="floating-button">+ </div>
        </a>
        <div class="element-container">


        </div>
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