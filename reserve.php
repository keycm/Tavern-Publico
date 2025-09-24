<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Reservation</title>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/reservation.css">
</head>
<body>

    <?php include 'partials/header.php'; ?>

    <section class="reservation-hero-section">
        <img src="images/1st.jpg" alt="Tavern Publico exterior at night" class="reservation-bg-image">
        <div class="reservation-overlay">
            <div class="reservation-container">
                <div class="reservation-form-card">
                    <h2>Schedule a Reservation</h2>
                    <form class="reservation-form" action="process_reservation.php" method="POST">
                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="resDate">Date</label>
                                <input type="date" id="resDate" name="resDate" required>
                            </div>
                            <div class="form-group">
                                <label for="resTime">Time</label>
                                <select id="resTime" name="resTime" required>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                    <option value="17:00">5:00 PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="numGuests">Number of Guests</label>
                            <select id="numGuests" name="numGuests" required>
                                <option value="1">1 Person</option>
                                <option value="2">2 People</option>
                                <option value="3">3 People</option>
                                <option value="4">4 People</option>
                                <option value="5">5 People</option>
                                <option value="6plus">6+ People (Call Us)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="resName">Name</label>
                            <input type="text" id="resName" name="resName" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <label for="resPhone">Phone Number</label>
                            <input type="tel" id="resPhone" name="resPhone" placeholder="Your Phone Number" required>
                        </div>
                        <div class="form-group">
                            <label for="resEmail">Email</label>
                            <input type="email" id="resEmail" name="resEmail" placeholder="Your Email" required>
                        </div>
                        <button type="submit" class="btn btn-primary confirm-reservation-btn">Confirm Reservation</button>
                    </form>
                </div>

                <div class="hours-card">
                    <h3>Hours of Operation</h3>
                    <p><strong>Monday - Thursday</strong><br>11:00 AM - 6:00 PM</p>
                    <p><strong>Friday - Saturday</strong><br>11:00 AM - 7:00 PM</p>
                    <p><strong>Sunday</strong><br>12:00 PM - 9:00 PM</p>
                    <p class="call-message">For parties of 6 or more, please call us directly at (123) 456-7890</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'partials/footer.php'; ?>

 <?php include 'partials/Signin-Signup.php'; ?>

    <script src="JS/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const resDateInput = document.getElementById('resDate');
            if (resDateInput) {
                const today = new Date();
                const day = String(today.getDate()).padStart(2, '0');
                const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
                const year = today.getFullYear();
                resDateInput.value = `${year}-${month}-${day}`;
            }
        });
    </script>
</body>
</html>