<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Menu</title>
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php
    include 'partials/header.php';
    include 'config.php';
    ?>

    <main>
        <section class="menu-section common-padding">
            <div class="container">
                <div class="menu-header">
                    <div class="category-buttons">
                        <button class="category-btn active" data-category="All">
                            <i class="fas fa-list"></i> All Items
                        </button>
                        <button class="category-btn" data-category="Specialty">
                            <i class="fas fa-utensils"></i> Specialty
                        </button>
                        <button class="category-btn" data-category="Breakfast">
                            <i class="fas fa-pizza-slice"></i> All Day Breakfast
                        </button>
                        <button class="category-btn" data-category="Lunch">
                            <i class="fas fa-hamburger"></i> Ala Carte/For Sharing
                        </button>
                        <button class="category-btn" data-category="Sizzlers">
                            <i class="fas fa-fish"></i> Sizzling Plates
                        </button>
                        <button class="category-btn" data-category="Coffee">
                            <i class="fas fa-pasta"></i> Cafe Drinks
                        </button>
                        <button class="category-btn" data-category="Cool Creations">
                            <i class="fas fa-leaf"></i> Frappe
                        </button>
                    </div>
                    <div class="search-sort">
                        <div class="search-bar">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search menu...">
                        </div>
                        <div class="sort-by">
                            <label for="sort-select">Sort by:</label>
                            <select id="sort-select">
                                <option value="popular">Popular</option>
                                <option value="price-low-high">Price (Low to High)</option>
                                <option value="price-high-low">Price (High to Low)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="menu-grid">
                    <?php
                    // Fetch menu items from the database
                    $sql = "SELECT * FROM menu ORDER BY category, name";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Loop through each row and display the menu item card
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="menu-item-card" data-category="' . htmlspecialchars($row['category']) . '">';
                            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                            echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                            echo '<div class="item-price-add">';
                            echo '<span class="price">$' . number_format($row['price'], 2) . '</span>';
                            echo '<button class="add-to-cart-btn"><i class="fas fa-plus"></i></button>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No menu items found. Please add some using the admin panel.</p>";
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoryButtons = document.querySelectorAll('.category-btn');
            const menuItems = document.querySelectorAll('.menu-item-card');
            const currentCategoryTitle = document.querySelector('.current-category-title');
            const searchInput = document.querySelector('.search-bar input');
            const sortBySelect = document.querySelector('.sort-by select');

            // Function to filter and display menu items based on category
            const filterMenuItems = (category) => {
                menuItems.forEach(item => {
                    const isVisible = category === 'All' || item.dataset.category === category;
                    item.style.display = isVisible ? '' : 'none';
                });
            };

            // Event listeners for category buttons
            categoryButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const category = button.dataset.category;
                    // Update active button
                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    filterMenuItems(category);
                    // Reset search filter
                    searchInput.value = '';
                });
            });

            // Search functionality
            searchInput.addEventListener('input', () => {
                const searchTerm = searchInput.value.toLowerCase();
                const activeCategory = document.querySelector('.category-btn.active').dataset.category;

                menuItems.forEach(item => {
                    const itemName = item.querySelector('h3').textContent.toLowerCase();
                    const isVisibleByCategory = (activeCategory === 'All' || item.dataset.category === activeCategory);
                    const isVisibleBySearch = itemName.includes(searchTerm);
                    item.style.display = (isVisibleByCategory && isVisibleBySearch) ? '' : 'none';
                });
            });

            // Sort functionality (client-side sort - visual only, not persistent)
            sortBySelect.addEventListener('change', () => {
                const sortValue = sortBySelect.value;
                const menuGrid = document.querySelector('.menu-grid');
                // Only sort currently visible items
                const visibleItems = Array.from(menuItems).filter(item => item.style.display !== 'none');

                visibleItems.sort((a, b) => {
                    const priceA = parseFloat(a.querySelector('.price').textContent.replace('$', ''));
                    const priceB = parseFloat(b.querySelector('.price').textContent.replace('$', ''));

                    if (sortValue === 'price-low-high') {
                        return priceA - priceB;
                    } else if (sortValue === 'price-high-low') {
                        return priceB - priceA;
                    }
                    return 0; // Keep original order if not sorting by price or 'popular'
                });

                // Re-append sorted items to the grid
                visibleItems.forEach(item => menuGrid.appendChild(item));
            });
        });
    </script>
</body>
</html>
