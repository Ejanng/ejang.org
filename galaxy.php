<?php
include 'config.php'; // Include the config file
require $db_url; // Ensure this file connects to your database

try {
    // Fetch all data from the Messages table
    $stmt = $conn->prepare("SELECT username, text FROM Messages");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    $gtag_id = 'G-Z5JM0NSZVH'; // Store your gtag ID securely
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gtag_id) ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($gtag_id) ?>');
    </script>
    <script src="reload.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galaxy Notes</title>
    <style>
        /* Reset and set full-screen layout */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background: #000; /* Base black background */
        }

        .galaxy {
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(63, 94, 251, 0.2), #000 90%);
        }

        /* Star styling */
        .star {
            position: absolute;
            background-color: white;
            border-radius: 50%;
            cursor: pointer;
            animation: colorChange 4s infinite, sizeChange 3s infinite alternate;
            transition: transform 0.2s;
        }

        .star:hover {
            transform: scale(1.3);
        }

        /* Animations for color and size transitions */
        @keyframes colorChange {
            0% { background-color: #ffffff; }    /* White */
            25% { background-color: #ffcc00; }   /* Yellow */
            50% { background-color: #ff3399; }   /* Pink */
            75% { background-color: #33ccff; }   /* Light Blue */
            100% { background-color: #66ff66; }  /* Green */
        }

        @keyframes sizeChange {
            0% { transform: scale(1); }
            50% { transform: scale(1.5); }
            100% { transform: scale(1); }
        }

        /* Modal styling */
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 30px 40px;
            border-radius: 12px;
            display: none;
            z-index: 1000;
            text-align: center;
            max-width: 500px;
            width: 80%;
            box-sizing: border-box;
        }

        .modal p {
            margin: 0;
            font-size: 1.1em;
        }

        .modal .username {
            font-size: 1.3em;
            margin-bottom: 10px;
            font-weight: normal;
        }

        .modal .message {
            font-size: 1.0em;
            margin-top: 5px;
        }

        .close-button {
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #f00;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .close-button:hover {
            background-color: #c00;
        }

        /* Styling the black hole */
        .blackhole {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100px;
            height: 100px;
            background-color: #111;
            border-radius: 50%;
            box-shadow: 0 0 30px 10px rgba(0, 0, 0, 0.7);
            transform: translate(-50%, -50%);
            cursor: pointer;
            text-align: center; /* Center the text inside the black hole */
        }

        .blackhole:hover::after {
        content: "Enter the Galaxy!";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 18px;
        font-weight: bold;
        background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
        padding: 5px 10px;
        border-radius: 5px;
        transition: opacity 0.3s ease;
        opacity: 1;
    }
    </style>
    <script>
        function showModal(username, message) {
            // Safely set content to the modal with HTML encoding
            const modal = document.getElementById('modal');
            const modalContent = document.getElementById('modal-content');
            modalContent.innerHTML = "<br><span class='message'>" + message + "</span><br><br><span class='username'>- " + username + "</span>";
            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="galaxy">
        <!-- Black Hole that leads to index.php -->
        <div class="blackhole" onclick="window.location.href='<?php echo $leave_a_note_url ?>';"></div>

        <?php
        foreach ($messages as $message) {
            // Randomize position and size for each star
            $size = rand(10, 20); // Size in pixels
            $top = rand(0, 90); // Top position (percentage)
            $left = rand(0, 90); // Left position (percentage)

            // Prepare the content for the modal
            // Ensure that data is properly escaped to prevent XSS
            $username = htmlspecialchars($message['username']);
            $text = htmlspecialchars($message['text']);

            // JavaScript-friendly escaping for passing data as arguments
            $escapedUsername = addslashes($username); 
            $escapedText = addslashes($text);

            // Output the star element
            echo "<div 
                class='star' 
                style='
                    width: {$size}px; 
                    height: {$size}px; 
                    top: {$top}%; 
                    left: {$left}%;
                ' 
                onclick=\"showModal('{$escapedUsername}', '{$escapedText}')\"></div>";
        }
        ?>
    </div>

    <!-- Modal for showing star information -->
    <div id="modal" class="modal">
        <p id="modal-content"></p>
        <button class="close-button" onclick="closeModal()">Close</button>
    </div>
</body>
</html>
