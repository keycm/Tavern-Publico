<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Contact Us</title>
    
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/contact.css">
</head>
<body>

    <?php include 'partials/header.php'; ?>

    <main>
        <section class="contact-info-section common-padding">
            <div class="container">
                <h2 class="section-heading">Get in Touch</h2>
                <div class="contact-grid">
                    <div class="contact-card">
                        <h3>Location</h3>
                        <p>123 Main Street, Guagua, Pampanga</p>
                        <p><a href="https://maps.app.goo.gl/YourGoogleMapsLink" target="_blank">View on Google Maps</a></p>
                    </div>
                    <div class="contact-card">
                        <h3>Reservations & Inquiries</h3>
                        <p>Phone: (045) 123-4567</p>
                        <p>Email: info@tavernpublico.com</p>
                    </div>
                    <div class="contact-card">
                        <h3>Hours of Operation</h3>
                        <p>Monday - Thursday: 11am - 10pm</p>
                        <p>Friday - Saturday: 11am - 12am</p>
                        <p>Sunday: 10am - 9pm</p>
                    </div>
                </div>

                <div class="contact-form-map-grid">
                    <div class="contact-form-container">
                        <h3>Send Us a Message</h3>
                        <form class="contact-form">
                            <div class="form-group">
                                <label for="contactName">Name</label>
                                <input type="text" id="contactName" name="contactName" required>
                            </div>
                            <div class="form-group">
                                <label for="contactEmail">Email</label>
                                <input type="email" id="contactEmail" name="contactEmail" required>
                            </div>
                            <div class="form-group">
                                <label for="contactSubject">Subject</label>
                                <input type="text" id="contactSubject" name="contactSubject">
                            </div>
                            <div class="form-group">
                                <label for="contactMessage">Message</label>
                                <textarea id="contactMessage" name="contactMessage" rows="6" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                   <div class="map-container">
                        <iframe src="http://maps.google.com/maps?q=269+Floridablanca+Road,+Jose+Abad+Santos+(Siran,+Guagua,+Pampanga)&output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>

    <?php include 'partials/Signin-Signup.php'; ?>

</div>
    <script>
        var modal = document.getElementById("signInModal");
        var openModalBtn = document.querySelector(".header-button");
        var closeButton = document.getElementsByClassName("close-button")[0];

        var signInPanel = document.getElementById("signInPanel");
        var registerPanel = document.getElementById("registerPanel");

        var switchToRegisterLinks = document.querySelectorAll(".switch-to-register");
        var switchToSignInLink = document.querySelector(".switch-to-signin");

        // When the user clicks the open modal button, show the modal and sign-in panel
        openModalBtn.onclick = function() {
          modal.style.display = "flex"; // Show the modal container
          // Ensure only signInPanel is active when opening
          signInPanel.classList.add("active");
          registerPanel.classList.remove("active");
        }

        // When the user clicks on <span> (x), close the modal
        closeButton.onclick = function() {
          modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal content, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }

        // Function to switch to register panel
        switchToRegisterLinks.forEach(function(link) {
            link.onclick = function(event) {
                event.preventDefault(); // Prevent default link behavior
                signInPanel.classList.remove("active");
                registerPanel.classList.add("active");
            };
        });

        // Function to switch back to sign-in panel
        if (switchToSignInLink) {
            switchToSignInLink.onclick = function(event) {
                event.preventDefault(); // Prevent default link behavior
                registerPanel.classList.remove("active");
                signInPanel.classList.add("active");
            };
        }
    </script>
     <script src="JS/main.js"></script>
</body>
</html>