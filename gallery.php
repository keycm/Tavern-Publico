<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Gallery</title>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/gallery.css">
</head>
<body>

<?php
include 'partials/header.php';
include 'config.php';
?>

<main>
    <section class="gallery-section common-padding">
        <div class="container">
            <h2 class="section-heading">A Glimpse Inside Tavern Publico</h2>
            <div class="image-grid">
                <?php
                // Fetch gallery images from the database
                $sql = "SELECT * FROM gallery ORDER BY id DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Loop through each row and display the gallery item
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="gallery-item">';
                        echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Tavern Gallery Image">';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No gallery images found.</p>";
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php
include 'partials/footer.php';
include 'partials/Signin-Signup.php';
?>
<script src="JS/main.js"></script>

</body>
</html>
