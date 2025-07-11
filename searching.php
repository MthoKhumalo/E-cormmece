<?php
    require_once('Config/config.inc.php');
    require_once('database/search/search_view.inc.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEARCH</title>
    <link rel="stylesheet" href="CSS/style.css">
    <!-- Optional: jQuery for handling AJAX search -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <!-- SEARCH BAR -->
    <div class="search-container">
        <form class="search-form" id="searchForm" method="POST" action="database/search/search.inc.php">
            <label for="productsearch">Search Products</label>
            <div class="search-input-container">
                <input type="text" id="productsearch" name="productsearch" placeholder="Search by category or product name..." required>
                <button type="submit">Search</button>
            </div>
        </form>
    </div>


</body>
</html>
