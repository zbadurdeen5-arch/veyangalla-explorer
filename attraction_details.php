<?php

require_once "config/database.php";
require_once "includes/header.php";


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    echo '
    <div class="container py-5">
        <div class="alert alert-danger text-center">
            Invalid attraction.
        </div>

        <div class="text-center">
            <a href="attractions.php" class="btn btn-success">
                Back to Places
            </a>
        </div>
    </div>
    ';

    require_once "includes/footer.php";
    exit;
}


$attraction_id = intval($_GET['id']);


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
    WHERE attractions.Attraction_ID = $attraction_id
";


$result = $conn->query($sql);



if (!$result || $result->num_rows == 0) {

    echo '
    <div class="container py-5">
        <div class="alert alert-warning text-center">
            Attraction not found.
        </div>

        <div class="text-center">
            <a href="attractions.php" class="btn btn-success">
                Back to Places
            </a>
        </div>
    </div>
    ';

    require_once "includes/footer.php";
    exit;
}


$place = $result->fetch_assoc();

?>

<section class="page-header">

    <h1>
        <?php echo htmlspecialchars($place['Place_Name']); ?>
    </h1>

    <p>
        Explore this attraction around Veyangalla.
    </p>

</section>


<section class="container py-5">

    <div class="row g-4">

       
        <div class="col-lg-6">

            <?php
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

            $imageFile = $imageFiles[$place['Place_Name']] ?? '';
            $imagePath = __DIR__ . '/assets/images/' . $imageFile;
            ?>

            <?php if ($imageFile !== '' && is_file($imagePath)): ?>

                <img
                    src="assets/images/<?php echo htmlspecialchars($imageFile); ?>"
                    alt="<?php echo htmlspecialchars($place['Place_Name']); ?>"
                    class="detail-image"
                >

            <?php else: ?>

                <div class="image-placeholder" style="height: 300px;">
                    Image not available
                </div>

            <?php endif; ?>

        </div>


        <div class="col-lg-6">

            <div class="detail-box">

                <span class="category-badge">

                    <?php
                    echo htmlspecialchars(
                        $place['Category_Name']
                    );
                    ?>

                </span>


                <h2 class="mb-4">

                    <?php
                    echo htmlspecialchars(
                        $place['Place_Name']
                    );
                    ?>

                </h2>


                <div class="mb-4">

                    <h5 class="detail-label">
                        Description
                    </h5>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $place['Description']
                        );
                        ?>
                    </p>

                </div>


                <div class="mb-4">

                    <h5 class="detail-label">
                        Distance
                    </h5>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $place['Distance']
                        );
                        ?>
                        km from Veyangalla
                    </p>

                </div>


                
<div class="detail-info-box">
    <h3>🕒 Opening Times</h3>

    <?php
    $openingTimes = trim($place['Opening_Times'] ?? '');

    if (
        $openingTimes === '' ||
        strtolower($openingTimes) === 'not specified'
    ) {
        $openingTimes = 'Please confirm visiting hours locally.';
    }
    ?>

    <p>
        <?= htmlspecialchars(
            $openingTimes,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>
</div>


                <div class="mb-4">

                    <h5 class="detail-label">
                        Travel Tips
                    </h5>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $place['Travel_Tips']
                        );
                        ?>
                    </p>

                </div>


                

<div class="detail-map-section">
    <h3>📍 Location Map</h3>

    <?php
    $mapQuery = trim($place['Map_Location'] ?? '');

    if ($mapQuery === '') {
        $mapQuery = trim($place['Place_Name']) . ', Sri Lanka';
    }

    $encodedLocation = urlencode($mapQuery);
    ?>

    <div class="map-container">
        <iframe
            src="https://maps.google.com/maps?q=<?= $encodedLocation ?>&output=embed"
            width="100%"
            height="350"
            style="border: 0;"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            title="Map showing <?= htmlspecialchars($place['Place_Name'], ENT_QUOTES, 'UTF-8') ?>">
        </iframe>
    </div>

    <a
        class="directions-btn"
        href="https://www.google.com/maps/dir/?api=1&destination=<?= $encodedLocation ?>"
        target="_blank"
        rel="noopener noreferrer">
        Get Directions →
    </a>
</div>



                <div class="mt-4">

                    <a
    href="plan.php?action=add&id=<?php echo $place['Attraction_ID']; ?>"
    class="btn btn-outline-success"
>
    Add to Plan
</a>
                    <a
                        href="attractions.php"
                        class="btn btn-outline-secondary"
                    >
                        Back to Places
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


<?php

require_once "includes/footer.php";

?>