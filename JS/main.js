document.addEventListener('DOMContentLoaded', () => {

    // --- Smooth Scrolling for Navigation Links ---
    // (Preserved from your original main.js)
    document.querySelectorAll('.main-nav ul li a, .reserve-now-btn, .footer-links ul li a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            // Check if the href is an internal anchor link (starts with #)
            // and not a PHP file link
            if (this.hash !== '' && this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const targetId = this.hash;
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - (document.querySelector('.main-header')?.offsetHeight || 0), // Adjust for fixed header
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // --- New: Highlight Active Navigation Link ---
    // This function sets the 'active-nav-link' class based on the current page.
    const highlightActiveNavLink = () => {
        let currentPath = window.location.pathname.split('/').pop();
        if (currentPath === '' || currentPath === '/') {
            currentPath = 'index.php'; // Default to index.php for root
        }

        const navLinks = document.querySelectorAll('.main-nav ul li a');

        navLinks.forEach(link => {
            link.classList.remove('active-nav-link'); // Remove from all first
            const linkPath = link.getAttribute('href').split('/').pop();

            if (currentPath === linkPath) {
                link.classList.add('active-nav-link'); // Add to the current one
            }
        });
    };

    highlightActiveNavLink(); // Call it when the DOM is loaded

    // New: Placeholder for "Reserve Now" button (if it exists on index.php)
    // The href="reserve.php" on the button now handles redirection,
    // so this JS listener is not strictly needed for redirection but can be for other effects.
    const reserveNowBtn = document.querySelector('.reserve-now-btn');
    if (reserveNowBtn) {
        reserveNowBtn.addEventListener('click', (e) => {
            // No need for e.preventDefault() if the button naturally links to reserve.php
        });
    }


    // --- Sign In/Sign Up Modal Functionality ---
    var modal = document.getElementById("signInUpModal");
    var openModalBtns = document.querySelectorAll(".signin-button"); // Targets the "Sign In/Sign Up" button
    var closeButton = document.querySelector(".modal .close-button");
    var signInPanel = document.getElementById("signInPanel");
    var registerPanel = document.getElementById("registerPanel");
    var switchToRegisterLinks = document.querySelectorAll(".switch-to-register");
    var switchToSignInLink = document.querySelector(".switch-to-signin");

    openModalBtns.forEach(function(openModalBtn) {
        // Open Modal - only if the button exists (i.e., user is not logged in)
        if (openModalBtn && !openModalBtn.classList.contains('profile-button')) { // Check if it's the sign-in/up button, not the profile button
            openModalBtn.onclick = function() {
                modal.style.display = "flex"; // Show the modal container
                // Ensure only signInPanel is active when opening
                signInPanel.classList.add("active");
                registerPanel.classList.remove("active");
            }
        }
    })

    // When the user clicks on <span> (x), close the modal
    if (closeButton) {
        closeButton.onclick = function() {
            modal.style.display = "none";
        }
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

    // Handle Registration Form Submission
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;

            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        username: username,
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    console.log(data.message);
                    // Optionally switch to sign-in panel after successful registration
                    signInPanel.classList.add("active");
                    registerPanel.classList.remove("active");
                    registerForm.reset(); // Clear the form
                } else {
                    console.error(data.message);
                }
            } catch (error) {
                console.error('Error during registration:', error);
            }
        });
    }

    // New: Handle Sign In Form Submission
    const signInForm = document.getElementById('signInForm');
    if (signInForm) {
        signInForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username_email = document.getElementById('loginUsernameEmail').value;
            const password = document.getElementById('loginPassword').value;

            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        username_email: username_email,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    console.log(data.message);
                    // Close modal and redirect if login is successful
                    modal.style.display = "none";
                    signInForm.reset(); // Clear the form
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Default redirect if not specified (e.g., refresh current page)
                        window.location.reload();
                    }
                } else {
                    console.error(data.message);
                }
            } catch (error) {
                console.error('Error during login:', error);
            }
        });
    }
    // --- End Sign In/Sign Up Modal Functionality ---

    // --- New: Profile Dropdown Functionality ---
    const profileButton = document.querySelector('.profile-button');
    const profileDropdown = document.querySelector('.profile-dropdown-content');

    if (profileButton && profileDropdown) {
        profileButton.addEventListener('click', () => {
            profileDropdown.classList.toggle('show-dropdown');
        });

        // Close the dropdown if the user clicks outside of it
        window.addEventListener('click', (event) => {
            if (!event.target.matches('.profile-button') && !event.target.matches('.profile-dropdown-content a')) {
                if (profileDropdown.classList.contains('show-dropdown')) {
                    profileDropdown.classList.remove('show-dropdown');
                }
            }
        });
    }


    // --- Slideshow functionality ---
    // (Preserved from your original main.js, with a check for slideshow elements)
    let slideIndex = 0;
    // Only call showSlides if there are elements with class 'mySlides'
    if (document.getElementsByClassName("mySlides").length > 0) {
        showSlides();
    }

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        if (slides.length > 0) { // Double-check inside function as well
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) { slideIndex = 1 }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 5000); // Change image every 5 seconds
        }
    }

}); // End of DOMContentLoaded

// Function to fetch notifications from the server
const fetchNotifications = async () => {
    try {
        const response = await fetch('get_notifications.php');
        const data = await response.json();

        const notificationCount = document.getElementById('notificationCount');
        const notificationDropdownContent = document.getElementById('notificationDropdownContent');

        if (data.success && data.notifications.length > 0) {
            // Update the badge count and show it
            notificationCount.textContent = data.notifications.length;
            notificationCount.style.display = 'block';

            // Clear old notifications and add new ones
            notificationDropdownContent.innerHTML = '';
            data.notifications.forEach(notification => {
                const notificationItem = document.createElement('a');
                notificationItem.href = '#'; // Or a link to a reservation details page
                notificationItem.classList.add('notification-item');
                notificationItem.dataset.reservationId = notification.reservation_id;

                const statusText = notification.status === 'Confirmed' ? 'accepted' : 'declined';
                notificationItem.innerHTML = `Your reservation for ${notification.res_date} has been <strong>${statusText}</strong>.`;
                notificationDropdownContent.appendChild(notificationItem);
            });
        } else {
            // Hide the badge and show 'No new notifications' message
            notificationCount.style.display = 'none';
            notificationDropdownContent.innerHTML = '<p class="no-notifications">No new notifications.</p>';
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

// Function to clear a single notification
const clearNotification = async (reservationId) => {
    try {
        const formData = new FormData();
        formData.append('reservation_id', reservationId);
        const response = await fetch('clear_notifications.php', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if (data.success) {
            // Re-fetch notifications to update the list
            fetchNotifications();
        }
    } catch (error) {
        console.error('Error clearing notification:', error);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // Check for the notification bell and setup the listener
    const notificationButton = document.querySelector('.notification-button');
    const notificationDropdownContent = document.getElementById('notificationDropdownContent');

    if (notificationButton) {
        // Fetch notifications on page load
        fetchNotifications();

        // Fetch new notifications every 30 seconds
        setInterval(fetchNotifications, 30000);

        notificationButton.addEventListener('click', (event) => {
            event.preventDefault();
            notificationDropdownContent.classList.toggle('show');
        });

        // Event listener for clicking a notification item
        notificationDropdownContent.addEventListener('click', (event) => {
            const target = event.target.closest('.notification-item');
            if (target) {
                const reservationId = target.dataset.reservationId;
                if (reservationId) {
                    clearNotification(reservationId);
                }
            }
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', (event) => {
            if (!event.target.closest('.notification-dropdown')) {
                notificationDropdownContent.classList.remove('show');
            }
        });
    }
});

// Add this to your main.js file, preferably inside the DOMContentLoaded listener

const notificationBell = document.querySelector('.notification-bell');
if (notificationBell) {
    notificationBell.addEventListener('click', () => {
        // Find the dot element inside the bell
        const notificationDot = notificationBell.querySelector('.notification-dot');
        if (notificationDot) {
            // Hide the dot by setting its display property to 'none'
            notificationDot.style.display = 'none';
        }
        // You could also add more logic here, like fetching new notifications
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const mainNav = document.querySelector('.main-nav');
    const body = document.querySelector('body');

    if (mobileNavToggle && mainNav) {
        mobileNavToggle.addEventListener('click', function() {
            // Toggle the 'nav-open' class on the main navigation
            mainNav.classList.toggle('nav-open');

            // Toggle the 'active' class on the button for the X animation
            this.classList.toggle('active');

            // Prevent scrolling of the page when the menu is open
            body.classList.toggle('no-scroll');
        });
    }

    // --- Existing Modal Logic --- //
    // I've included a generic version of your modal logic here
    // to keep everything in one file. You can adapt this as needed.

    const modal = document.getElementById("signInModal") || document.getElementById("signInUpModal");
    const openModalBtn = document.querySelector(".signin-button"); // Targets both header and reserve buttons
    const reserveNowBtn = document.querySelector(".reserve-now-section .signin-button");
    const closeButton = document.querySelector(".close-button");

    const signInPanel = document.getElementById("signInPanel");
    const registerPanel = document.getElementById("registerPanel");

    const switchToRegisterLinks = document.querySelectorAll(".switch-to-register");
    const switchToSignInLink = document.querySelector(".switch-to-signin");

    const openModal = function() {
        if (modal) {
            modal.style.display = "flex";
            if (signInPanel && registerPanel) {
                signInPanel.classList.add("active");
                registerPanel.classList.remove("active");
            }
        }
    };

    if (openModalBtn) {
        openModalBtn.addEventListener('click', openModal);
    }
    if (reserveNowBtn) {
        reserveNowBtn.addEventListener('click', openModal);
    }

    if (closeButton) {
        closeButton.onclick = function() {
            modal.style.display = "none";
        };
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    switchToRegisterLinks.forEach(function(link) {
        link.onclick = function(event) {
            event.preventDefault();
            if (signInPanel && registerPanel) {
                signInPanel.classList.remove("active");
                registerPanel.classList.add("active");
            }
        };
    });

    if (switchToSignInLink) {
        switchToSignInLink.onclick = function(event) {
            event.preventDefault();
            if (signInPanel && registerPanel) {
                registerPanel.classList.remove("active");
                signInPanel.classList.add("active");
            }
        };
    }
});