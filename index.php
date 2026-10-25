
<?php
require_once "config/database.php";

$imageFiles = [
    'Batamulla Kanda Buddha Statue' => 'batamulla-kanda.jpg',
    'Kodigala Mountain Nehinna' => 'kodigala-mountain.jpeg',
    'Athweltota Pilithuda' => 'athweltota-pilithuda.jpeg',
    'Warakagoda Gallena Rajmaha Viharaya' => 'warakagoda-viharaya.jpeg',
    'Rannagala Ella' => 'rannagala-ella.jpeg',
    'Ginigedara Ella, Bulathsinhala' => 'ginigedara-ella.jpg',
    'Rubber Research Institute of Sri Lanka, Agalawatta' => 'rrisl.jpg',
    'Pahiyangala Cave' => 'pahiyangala-cave.jpg',
    'Thambadola Ella (Polgampola Ella)' => 'thambadola-ella.jpg',
    'Julee Ella' => 'julee-ella.jpeg',
    'Thudugala Ella' => 'thudugala-ella.jpg',
    'Brief Garden' => 'brief-garden.jpg'
];

require_once "includes/header.php";
?>


<section class="hero-section"
    style="background-image:
    linear-gradient(rgba(0,45,25,0.60), rgba(0,35,18,0.70)),
    url('http://localhost/veyangalla_explorer/assets/images/home-background.jpg');">

    <div class="hero-content">

        <div class="location-badge">
            📍 Veyangalla, Sri Lanka
        </div>

        <h1>Explore Veyangalla</h1>

        <p class="hero-description">
            Discover beautiful places, breathtaking waterfalls,
            cultural sites, mountains and hidden natural treasures
            around Veyangalla.
        </p>

        <p class="hero-distance">
            Explore attractions within a <strong>25 km radius</strong>
            and plan your perfect one-day trip.
        </p>

        <div class="hero-buttons">
            <a href="attractions.php" class="hero-btn hero-btn-light">
                Explore Places →
            </a>

            <a href="plan.php" class="hero-btn hero-btn-green">
                Plan Your Trip
            </a>
        </div>

    </div>
</section>



<section class="container py-5">
    <div class="text-center">
        <h2 class="section-title">Welcome to Veyangalla Explorer</h2>
        <p class="section-subtitle">
            Your simple guide to discovering local attractions
            and planning a one-day visit around Veyangalla.
        </p>
    </div>
</section>

<section class="container pb-5">
    <div class="text-center">
        <h2 class="section-title">Find a Place to Visit</h2>
        <p class="section-subtitle">Search for attractions by name.</p>
    </div>

    <form action="attractions.php" method="GET" class="search-box">
        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control form-control-lg"
                placeholder="Search for a place..."
            >
            <button type="submit" class="btn btn-success btn-lg">
                Search
            </button>
        </div>
    </form>
</section>

<section class="container pb-5">
    <div class="text-center">
        <h2 class="section-title">Explore by Category</h2>
        <p class="section-subtitle">
            Choose a category to discover places that match your interests.
        </p>
    </div>

    <div class="text-center category-list">
        <?php
        $categorySql = "
            SELECT Category_ID, Category_Name
            FROM categories
            ORDER BY Category_Name ASC
        ";
        $categoryResult = $conn->query($categorySql);

        if ($categoryResult):
            while ($cat = $categoryResult->fetch_assoc()):
        ?>
            <a
                href="attractions.php?category=<?php echo (int)$cat['Category_ID']; ?>"
                class="category-btn"
            >
                <?php echo htmlspecialchars($cat['Category_Name']); ?>
            </a>
        <?php
            endwhile;
        endif;
        ?>
    </div>
</section>

<section class="container pb-5">
    <div class="text-center">
        <h2 class="section-title">Explore All Places</h2>
        <p class="section-subtitle">
            Discover the attractions available around Veyangalla.
        </p>
    </div>

    <div class="row g-4">
        <?php
        $sql = "
            SELECT
                attractions.Attraction_ID,
                attractions.Place_Name,
                attractions.Description,
                attractions.Distance,
                categories.Category_Name
            FROM attractions
            INNER JOIN categories
                ON attractions.Category_ID = categories.Category_ID
            ORDER BY attractions.Attraction_ID ASC
        ";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            while ($place = $result->fetch_assoc()):
                $placeName = $place['Place_Name'];
                $imageFile = $imageFiles[$placeName] ?? '';
                $imagePath = __DIR__ . '/assets/images/' . $imageFile;
        ?>
            
                <div class="col-12 col-sm-6 col-lg-4">
                <div class="attraction-card h-100">

                    <div class="attraction-image">
                        <?php if (
                            $imageFile !== '' &&
                            is_file($imagePath)
                        ): ?>
                            <img
                                src="assets/images/<?php echo rawurlencode($imageFile); ?>"
                                alt="<?php echo htmlspecialchars($placeName); ?>"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="image-placeholder">
                                Image not available
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <span class="category-badge">
                            <?php echo htmlspecialchars($place['Category_Name']); ?>
                        </span>

                        <h5 class="card-title">
                            <?php echo htmlspecialchars($placeName); ?>
                        </h5>

                        <p class="card-text">
                            <?php echo htmlspecialchars($place['Description']); ?>
                        </p>

                        <p class="mb-3">
                            <strong>Distance:</strong>
                            <?php echo htmlspecialchars($place['Distance']); ?> km
                        </p>

                        <a
                            href="attraction_details.php?id=<?php echo (int)$place['Attraction_ID']; ?>"
                            class="btn btn-explore"
                        >
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        <?php
            endwhile;
        else:
        ?>
            <div class="col-12">
                <p class="text-center text-muted">
                    No attractions are currently available.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-4">
        <a href="attractions.php" class="btn btn-success btn-lg">
            View All Places
        </a>
    </div>
</section>

<section class="container pb-5">
    <div class="p-5 text-center bg-success text-white rounded">
        <h2>Plan Your One-Day Visit</h2>
        <p class="mt-3">
            Select attractions and create a simple one-day visit plan
            around Veyangalla.
        </p>
        <a href="plan.php" class="btn btn-light btn-lg mt-2">
            Start Planning
        </a>
    </div>
</section>

<?php require_once "includes/footer.php"; ?>
