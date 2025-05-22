<!DOCTYPE html>
<html>
<head>
    <title>My Laravel App</title>
    <style>
        body {
            margin: 0; /* Remove default body margin */
            display: flex;
            flex-direction: column; /* Arrange header, content, footer vertically */
            min-height: 100vh; /* Ensure the body takes at least the full viewport height */
            font-family: sans-serif; /* A nice default font */
            color: #333; /* Dark gray text for readability */
        }

        header {
            background-color: #ADD8E6; /* Light blue for the header */
            color: white; /* White text on the blue background */
            padding: 20px;
            text-align: center;
        }

        main {
            flex-grow: 1; /* Allows the main content to take up remaining vertical space */
            padding: 20px;
        }

        footer {
            background-color: #4682B4; /* Steel blue for the footer */
            color: white; /* White text on the blue background */
            padding: 10px;
            text-align: center;
            font-size: 0.8em; /* Slightly smaller font for the footer text */
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

    <footer>
        <p>&copy; 2025 My Laravel App</p>
    </footer>
</body>
</html>