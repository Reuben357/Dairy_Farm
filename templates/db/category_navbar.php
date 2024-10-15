<?php 
// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signin.php");
    exit();
}
?>
<!-- navbar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --main-color: #a8a62d;
            --main-light-color: #c0b15c;
            --container-color: #f8f7fc;
            --text-color: #1a1d22;
            --bg-color: #fff;

        }
        /* Basic styling for the navbar */
        .navbar {
            background-color: white;
            overflow: hidden;
            display: flex;
            padding-left: 10%;
            justify-content: space-between;
            padding: 14px 20px;
            align-items: center;
            column-gap: 2rem;
        }

        /* Navbar items styling */
        .navbar a {
            color: var(--text-color);
            position: relative;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.2s linear;
        }

        /* Hover effects for the navbar items */
        .navbar a:hover {
            background-color: #575757;
            color: white;
        }

        /* Styling for the brand or logo section (optional) */
        .navbar .brand {
            font-weight: bold;
            font-size: 18px;
        }
        .container{
            max-width: 938px;
            margin-left: auto;
            margin-right: auto;
        }
        .nav{
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="nav container">
    <ul class="navbar">
                <a href="home_page.php" class="active">Home</a>
                <a href="#featured">Featured</a>
                <a href="category_products.php">Categories</a>
                <a href="contactme_page.php">Contact Me</a>
                <a href="?logout=1">Logout</a>
            </ul>
        </div>
</body>
</html>
