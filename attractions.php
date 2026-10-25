
<?php

require_once "config/database.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? (int) $_GET['category'] : 0;

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

$sql = "
    SELECT
        attractions.Attraction_ID,
        attractions.Place_Name,
        attractions.Description,
        attractions.Opening_Times,
        attractions.Distance,
        attractions.Travel_Tips,
        attractions.Map_Location,
        categories.Category_Name
    FROM attractions
    INNER JOIN categories
        ON attractions.Category_ID = categories.Category_ID
    WHERE 1 = 1
";

$params = [];
$types = '';

if ($search !== '') {
    $sql .= "
        AND (
            attractions.Place_Name LIKE ?
            OR attractions.Description LIKE ?
        )
    ";

    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

if ($category > 0) {
    $sql .= " AND attractions.Category_ID = ?";
    $params[] = $category;
    $types .= 'i';
}

$sql .= " ORDER BY attractions.Place_Name ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Unable to prepare the attraction search.");
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$categoryResult = $conn->query("
    SELECT Category_ID, Category_Name
    FROM categories
    ORDER BY Category_Name ASC
");

require_once "includes/header.php";
?>

<section class="page-header">
    <h1>Explore Places</h1>
    <p>
        Discover attractions within a 25 km radius of Veyangalla.
    </p>
</section>

<section class="container py-5">
    <div class="text-center mb-4">
        <h2 class="section-title">Find Your Next Place</h2>
        <p class="section-subtitle">
            Search for an attraction or filter places by category.
        </p>
    </div>

    <form action="attractions.php" method="GET">
        <div class="row g-3">

            <div class="col-md-8">
                <input
                    type="text"
                    name="search"
                    class="form-control form-control-lg"
                    placeholder="Search for a place..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >
            </div>

            <div class="col-md-4">
                <select
                    name="category"
                    class="form-select form-select-lg"
                >
                    <option value="0">All Categories</option>

                    <?php if ($categoryResult): ?>
                        <?php while ($cat = $categoryResult->fetch_assoc()): ?>
                            <option
                                value="<?php echo (int) $cat['Category_ID']; ?>"
                                <?php echo ($category === (int) $cat['Category_ID']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($cat['Category_Name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    Search
                </button>

                <a
                    href="attractions.php"
                    class="btn btn-outline-secondary btn-lg px-5"
                >
                    Clear
                </a>
            </div>

        </div>
    </form>
</section>

<section class="container pb-5">
    <div class="row g-4">

        <?php if ($result && $result->num_rows > 0): ?>

            <?php while ($place = $result->fetch_assoc()): ?>

                <?php
                $placeName = $place['Place_Name'];
                $imageFile = $imageFiles[$placeName] ?? '';
                $imagePath = __DIR__ . '/assets/images/' . $imageFile;
                $imageExists = ($imageFile !== '' && is_file($imagePath));
                ?>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="attraction-card h-100">

                        
                        <div class="attraction-image">
                            <?php if ($imageExists): ?>
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
                                <?php echo htmlspecialchars((string) $place['Distance']); ?>
                                km
                            </p>

                            <a
                                href="attraction_details.php?id=<?php echo (int) $place['Attraction_ID']; ?>"
                                class="btn btn-explore"
                            >
                                View Details
                            </a>

                        </div>
                    </div>
                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="col-12">
                <div class="alert alert-warning text-center">
                    No attractions found. Try another search or category.
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php
$stmt->close();
require_once "includes/footer.php";
?>
