<?php
// 400 header to indicate a bad request
http_response_code(400);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>400 - Bad Request</title>
    <style>
        /* General Reset */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Container */
        .container {
            max-width: 600px;
            padding: 20px;
        }

        h1 {
            font-size: 4rem;
            margin: 0;
            color: #dc3545;
        }

        p {
            font-size: 1.2rem;
            margin: 20px 0;
            color: #495057;
        }

        a {
            color: #007bff;
            text-decoration: none;
            font-size: 1rem;
            border: 2px solid #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        a:hover {
            background: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>400</h1>
        <p>Oops! It looks like your request couldn't be processed.</p>
        <a href="/">Go Back Home</a>
    </div>
</body>
</html>
