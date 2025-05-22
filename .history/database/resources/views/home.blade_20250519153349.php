<!DOCTYPE html>
<html>
<head>
    <title>My Laravel App</title>
    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: sans-serif;
            color: #333;
        }

        header {
            background-color: #ADD8E6;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            flex-grow: 1;
            padding: 20px;
            display: flex; /* Enable flexbox for centering */
            flex-direction: column; /* Stack content vertically */
            align-items: center; /* Center items horizontally */
            justify-content: center; /* Center items vertically */
        }

        main p {
            text-align: center; /* Center the text within the paragraph */
        }

        .auth-buttons {
            display: flex; /* Arrange buttons horizontally */
            gap: 10px; /* Space between buttons */
            margin-top: 20px; /* Add some space above the buttons */
        }

        .auth-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .auth-buttons button:first-child {
            background-color: #5cb85c; /* Green for Login */
            color: white;
        }

        .auth-buttons button:last-child {
            background-color: #007bff; /* Blue for Signup */
            color: white;
        }

        footer {
            background-color: #4682B4;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <header>
        <h1>My Laravel App</h1>
    </header>

    <main>
        <p>This is the main content of my Laravel application.</p>
        <p>You can add more content below.</p>
    </main>

    <div class="auth-buttons">
        <button onclick="window.location.href='{{ url('/login') }}'">LOGIN</button>
        <button onclick="window.location.href='{{ url('/signup') }}'">SIGNUP</button>
    </div>

    <footer>
        <p>&copy; 2025 My Laravel App</p>
    </footer>

    <script>
        // You might need to adjust the URL generation based on your Laravel routing setup.
        // The `url()` helper function is commonly used in Blade templates.
        // If you're not within a Blade context here, ensure the URLs are correctly formed.
        // For example, if your routes are simply "/login" and "/signup", you could do:
        // <button onclick="window.location.href='/login'">LOGIN</button>
        // <button onclick="window.location.href='/signup'">SIGNUP</button>
    </script>
</body>
</html>