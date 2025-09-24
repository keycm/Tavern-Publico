<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Events</title>
    <link rel="stylesheet" href="CSS/main.css">
</head>
<body>

<?php
// Include the database configuration file
include 'partials/header.php';
include 'config.php';
?>

<main>
    <section class="upcoming-events-section common-padding">
        <div class="container">
            <h2 class="section-heading">Our Event Calendar</h2>
            <div class="events-grid">
                <?php
                // Fetch events from the database
                $sql = "SELECT * FROM events ORDER BY date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Loop through each row and display the event card
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="event-card">';
                        echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '">';
                        echo '<span class="event-date">' . htmlspecialchars($row['date']) . '</span>';
                        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                        echo '<a href="#" class="btn primary-btn">Learn More</a>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No upcoming events found. Please check back later!</p>";
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
