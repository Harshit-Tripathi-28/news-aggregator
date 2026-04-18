<?php
include("includes/auth_check.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>News Aggregator</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="logo">📰 News Aggregator</div>
        <div class="nav-right">
            <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a class="nav-btn" href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="main-wrapper">
        <h1>🔥 Trending News</h1>

        <div class="controls">
            <select id="category">
                <option value="general">General</option>
                <option value="technology">Technology</option>
                <option value="business">Business</option>
                <option value="sports">Sports</option>
                <option value="health">Health</option>
                <option value="science">Science</option>
                <option value="entertainment">Entertainment</option>
            </select>

            <button onclick="loadNews()">Load News</button>
            <button onclick="loadSaved()">Saved News</button>
        </div>

        <div id="news" class="news-grid"></div>
    </div>

    <script src="script.js"></script>
</body>
</html>