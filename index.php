<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice billing system</title>

    <link rel="stylesheet" href="styles.css">
</head>

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
    <section class="main">
        <h1>Voice Based Billing System in Kannada</h1>
        <div class="newslatter">
            <form id="nameForm" action="index.php" method="post">
                <input placeholder="ನಿಮ್ಮ ಹೆಸರನ್ನು ಹೇಳಿ" type="text" id="nameInput" name="name" required>


        </div>
    </section>


    <div class="btns">
        <button class="button button1" id="playAndSpeakButton"> Speak now</button>
        <button class="button button1" type="submit" name="submit" value="Submit">Submit</button>
    </div>

    </form>


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


    <script>
        window.onload = function() {
            const playAndSpeakButton = document.getElementById('playAndSpeakButton');
            const nameInput = document.getElementById('nameInput');
            const recognition = new webkitSpeechRecognition(); // This is a speech recognition instance
            recognition.lang = 'kn-IN'; // Set the language to Kannada

            // Configure the recognition settings
            recognition.continuous = false; // Single shot mode
            recognition.interimResults = true; // Show interim results (real-time feedback)

            playAndSpeakButton.addEventListener('click', function() {
                recognition.start(); // Start recognizing speech
            });

            recognition.onresult = function(event) {
                const current = event.resultIndex; // Index of the latest speech result
                const transcript = event.results[current][0].transcript; // Get the transcript of recognized text
                nameInput.value = transcript; // Display the transcript in the input box
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error', event.error); // Log errors to the console
            };

            recognition.onend = function() {
                // Handle end of speech recognition
                console.log('Speech recognition service disconnected');
            };
        }
    </script>

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
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("playAndSpeakButton");

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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const playAndSpeakButton = document.getElementById('playAndSpeakButton');
        const outputDiv = document.getElementById('output');
        const nameInput = document.getElementById('nameInput');
        const nameForm = document.getElementById('nameForm');
        const recognition = new window.webkitSpeechRecognition();
        recognition.lang = 'kn-IN';

        playAndSpeakButton.addEventListener('click', () => {
            const audio = new Audio('audio/voice01.mp3');
            audio.play();
            setTimeout(() => {
                recognition.start();
            }, 3000);
        });

        recognition.onresult = (event) => {
            const name = event.results[0][0].transcript.trim();
            // outputDiv.innerText = `Recognized Name: ${name}`;
            nameInput.value = name; // Set the recognized name in the input field
        };

        // nameForm.addEventListener('submit', (event) => {
        //     event.preventDefault(); // Prevent form submission for demonstration purposes
        //     const enteredName = nameInput.value.trim();
        //     alert(`Submitted Name: ${enteredName}`);
        //     // You can process the submitted name as needed (e.g., send it to a server)
        // });
    });
</script>
<?php
include 'connect_db.php';
// ini_set("display_errors", "1");
// ini_set("display_startup_errors", "1");
// error_reporting(E_ALL);



if (isset($_POST['submit'])) {

    $data = array();
    $data = $_POST;
    $rawData = trim($data['name']);
    unset($data['submit']);
    $table_name = 'add_customer';


    $ids =  GetRow("SELECT id FROM add_customer WHERE name = '$rawData'  ", $con);


    if ($ids) {
        $id = $ids['id'];
        echo "<script>window.location.href='take_order.php?id=$id';</script>";
    } else {
        $data['name'] = $rawData;

        $result = insert_data_id($data, $table_name, $con);
        echo "<script>window.location.href='take_order.php?id=$result';</script>";
    }
}


?>
<!-- --------------------voice js------------------------- -->


</html>