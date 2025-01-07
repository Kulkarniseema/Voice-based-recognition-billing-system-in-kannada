<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>take order</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>

<?php


include 'connect_db.php';


if (isset($_GET['id'])) {
    $cust_id = $_GET['id'];
    $cust_name = GetAllA("SELECT * FROM customer WHERE cust_id = '$cust_id'  ", $con);
    $old_order = GetAllA("SELECT * FROM orders WHERE cust_id = '$cust_id' ORDER BY timestamp DESC ", $con);
    $length = count($old_order);


?>

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
        <section class="order">
            <div class="left">
                <h1>Orderd list</h1>
                <div id="resultContainer">
                    <textarea id="resultTextarea" rows="20" cols="80"></textarea>

                    <div class="buttons">
                        <button class="button2 button1" id="startButton">Speak Now</button>

                        <form id="submitForm" action="take_order.php?id=<?php echo $cust_id ?>" method="post">

                            <input type="hidden" name="items_after_integer" id="itemsAfterIntegerInput" value="" />
                            <input class="button2 button1" type="submit" name="submit" value="Submit" />
                        </form>
                    </div>
                </div>

            </div>
            <div class="right">
                <h1>Previous order</h1>
                <section>
                    <!--for demo wrap-->

                    <div class="tbl-header">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th> Add</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tbl-content">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                                <?php
                                for ($i = 0; $i < $length; $i++) {
                                    $order = $old_order[$i]; ?>
                                    <tr>
                                        <td><?php echo date_ch($order['timestamp']) ?></td>
                                        <td>
                                            <?php
                                            $items = explode(',', $order['items_after_integer']);
                                            echo "<ul>";
                                            foreach ($items as $item) {
                                                // Trim each item to remove unwanted spaces
                                                $item = trim($item);
                                                echo "<li>$item</li>";
                                            }
                                            echo "</ul>";

                                            ?>
                                        </td>
                                        <!-- <td>
                                            <button class="  button3 button_round" id="addItemBtns">+</button>
                                        </td> -->
                                        <td><button class="addItemBtns button3 button_round" id="addItemBtns">+</button></td>


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
        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="bars">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>

        </div>

        <!-- Script to add the previous items into the list -->
        <script>
            $(document).ready(function() {
                $('.addItemBtns').on('click', function() {
                    var items = $(this).closest('tr').find('ul li').map(function() {
                        return '• ' + $(this).text();
                    }).get();

                    var currentVal = $('#resultTextarea').val().trim();

                    if (currentVal !== '') {
                        currentVal += '\n';
                    }

                    currentVal += items.join('\n');
                    $('#resultTextarea').val(currentVal);
                });
            });
        </script>

        </script>


        <script>
            let recognizedHistory = [];

            document.getElementById('startButton').addEventListener('click', startMicrophone);
            document.getElementById('submitForm').addEventListener('submit', prepareData);

            function startMicrophone() {
                const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();

                recognition.lang = 'kn-IN';

                recognition.onresult = function(event) {
                    const lastResult = event.results[event.results.length - 1][0].transcript;

                    if (event.results[0].isFinal) {
                        recognizedHistory.push(addCommaBeforeIntegers(lastResult));
                        displayInTextarea();
                    }
                };

                recognition.onerror = function(event) {
                    console.error('Speech recognition error:', event.error);
                };

                recognition.start();
            }

            function addCommaBeforeIntegers(text) {
                const integerPattern = /\b\d+\b/g;

                return text.replace(integerPattern, (match, index) => (index === 0 ? match : ',' + match));
            }

            function displayInTextarea() {
                const resultTextarea = document.getElementById('resultTextarea');

                resultTextarea.value = recognizedHistory.map(createListItems).join('\n\n');

                document.getElementById('submitForm').style.display = recognizedHistory.length > 0 ? 'block' : 'none';
            }

            function createListItems(text) {
                const words = text.split(/\s*,\s*/);
                return words.map(word => `• ${word}`).join('\n');
            }

            function submitRecognition(event) {
                document.getElementById('itemsAfterIntegerInput').value = recognizedHistory.join(', ');


            }

            function prepareData() {
                var textareaContent = document.getElementById('resultTextarea').value;

                var processedText = textareaContent.replace(/• /g, '').replace(/\n/g, ',');

                document.getElementById('itemsAfterIntegerInput').value = processedText;
            }
        </script>
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
        <!-- ============================popup========= -->
        <script>
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the button that opens the modal
            var btn = document.getElementById("startButton");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on the button, open the modal
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            function closeModal() {
                modal.style.display = "none";
            }
            setTimeout(closeModal, 5000);
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>

    </body>



    <?php

    // ini_set("display_errors", "1");
    // ini_set("display_startup_errors", "1");
    // error_reporting(E_ALL);

    if (isset($_POST['submit'])) {

        $item_name = GetAllA("SELECT * FROM item_list WHERE del=0 ", $con);

        $cust_id = $_GET['id'];

        $data = array();
        $data = $_POST;

        $rawData = trim($data['items_after_integer']);
        $data = explode(',', $rawData);
        $final_items = [];
        foreach ($data as $item) {
            preg_match_all('/[^\s,]+/', $item, $matches); // Match non-space and non-comma characters
            $final_items[] = $matches[0]; // Extract the matched words and store in the final array
        }

        $separateArray = array();

        // Loop through the main array and extract index [2] values
        foreach ($final_items as $item) {
            $separateArray[] = $item[2];
        }

        // Output the separate array
        // print_array($separateArray);
        // print_array($final_items);

        $presentItems = array(); // Array to store present items
        $absentItems = array();  // Array to store absent items

        // Loop through Array1 items and check if they exist in Array2
        foreach ($separateArray as $item) {
            $found = false;
            foreach ($item_name as $item2) {
                if ($item == $item2["item_name"]) {
                    $presentItems[] = $item;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $absentItems[] = $item;
            }
        }

        // Displaying results
        // echo "Present Items:\n";
        // print_array($presentItems);

        // echo "\nAbsent Items:\n";
        // print_array($absentItems);

        $presentIndices = array();
        $absentIndices = array();

        foreach ($data as $element) {
            $found = false;
            foreach ($presentItems as $word) {
                if (strpos($element, $word) !== false) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $presentData[] = $element;
            } else {
                $absentData[] = $element;
            }
        }

        if (!empty($absentItems)) {
            echo "<script>";
            echo "alert('The following items are not present: " . implode(", ", $absentItems) . "');";
            echo "</script>";
        }

        // echo "Data where the word is present: ";
        // print_array($presentData);
        $presentData = implode(',', $presentData);
        $rawDatas = trim($presentData);
        $datas = explode(',', $rawDatas);
        // print_array($presentData);
        // print_array($datas);
        // exit;
        // echo "Data where the word is absent: ";
        // print_r($absentData);

        unset($data['submit']);
        $dat['cust_id'] = $cust_id;
        $dat['items_after_integer'] = $presentData;
        // print_array($data);
        // exit;
        $table_name = 'orders';

        $result = insert_data_id($dat, $table_name, $con);


        for ($i = 0; $i < count($datas); $i++) {
            $bill_itms = explode(' ', $datas[$i]);
            $rate = GetAllA("SELECT * FROM item_list WHERE item_name = '$bill_itms[2]' ", $con);
            $rate = $rate[0]['price'];



            $stmt = $con->prepare("INSERT INTO customer (cust_id, order_id, item_name, item_quantity, item_price) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issii", $cust_id, $result, $bill_itms[2], $bill_itms[0], $rate);
            $stmt->execute();
            $stmt->close();
        }

        echo "<script>window.location.href='generate_bill.php?id=$cust_id&order_id=$result';</script>";
    }


    ?>

</html>