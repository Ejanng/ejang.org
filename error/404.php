<?php
// 404 header to indicate the page was not found
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        /* General Reset */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Minimalist Styling */
        .container {
            max-width: 600px;
            padding: 20px;
        }

        h1 {
            font-size: 5rem;
            margin: 0;
            color: #ff4757;
        }

        p {
            font-size: 1.2rem;
            margin: 20px 0;
            color: #dcdcdc;
        }

        a {
            color: #ff4757;
            text-decoration: none;
            font-size: 1rem;
            border: 2px solid #ff4757;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        a:hover {
            background: #ff4757;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>We can't seem to find the page you're looking for.</p>
        <a href="/">Go Home</a>
    </div>
</body>
</html>
