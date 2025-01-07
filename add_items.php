<?php
include 'connect_db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <section>
        <form action="add_items.php" method="post" enctype="multipart/form-data">
            <div class="box">
                <h1>Add Items </h1>
                <div class="col-3 input-effect">
                    <input class="effect-16 form-control" type="text" placeholder="" name="item_name">
                    <label>Item Name</label>
                    <span class="focus-border"></span>
                </div>
                <div class="col-3 input-effect">
                    <input class="effect-16 form-control" type="text" placeholder="" name="quantity">
                    <label>Price</label>
                    <span class="focus-border"></span>
                </div>
                <div class="col-3 input-effect">
                    <input class="effect-16 form-control" type="text" placeholder="" name="price">
                    <label>Quantity</label>
                    <span class="focus-border"></span>
                </div>
                <input type="submit" name="submit" class="button2 button1" value="Submit" />

            </div>

    </section>
    </form>
    <?php

    if (isset($_POST['submit'])) {
        $data = array();
        $data = $_POST;
        $data['item_name'] = $_POST['item_name'];
        $data['price'] = $_POST['price'];
        unset($data['submit']);
        $table_name = 'item_list';
        $result = insert_data_id($data, $table_name, $con);
        echo "<script>window.location.href='add_items.php';</script>";
    }

    if (isset($_POST['edit'])) {
        $data = array();
        $data = $_POST;
        $data['stream'] = strtoupper($data['stream']);
        $exe = Execute("Update student set stream='" . $data['stream'] . "' where stream='" . $data['old_stream'] . "' ", $con);
        unset($data['edit']);
        unset($data['old_stream']);
        $id = $_POST['id'];
        $table_name = 'stream';
        $where = " id=" . $id . " ";
        $result = update_data($data, $table_name, $where, $con);
        echo "<br/><br/><span>Data Inserted successfully...!!</span>";
        echo "<script>window.location.href='stream_list.php';</script>";
    }

    ?>

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

</html>