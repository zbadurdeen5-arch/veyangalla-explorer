<?php

session_start();

require_once "config/database.php";


if (!isset($_SESSION['trip_plan'])) {
    $_SESSION['trip_plan'] = [];
}


if (
    isset($_GET['action']) &&
    $_GET['action'] === 'add' &&
    isset($_GET['id'])
) {

    $id = intval($_GET['id']);

    if ($id > 0) {

        if (!in_array($id, $_SESSION['trip_plan'])) {

            $_SESSION['trip_plan'][] = $id;
        }
    }

    
    header("Location: plan.php");
    exit;
}


if (
    isset($_GET['action']) &&
    $_GET['action'] === 'remove' &&
    isset($_GET['id'])
) {

    $id = intval($_GET['id']);

    $_SESSION['trip_plan'] = array_values(
        array_diff($_SESSION['trip_plan'], [$id])
    );

    header("Location: plan.php");
    exit;
}


if (
    isset($_GET['action']) &&
    $_GET['action'] === 'clear'
) {

    $_SESSION['trip_plan'] = [];

    header("Location: plan.php");
    exit;
}


require_once "includes/header.php";

?>

<section class="page-header">

    <h1>Plan Your One-Day Visit</h1>

    <p>
        Select attractions and create a simple visit plan.
    </p>

</section>


<section class="container py-5">

    <div class="text-center mb-4">

        <h2 class="section-title">
            Your Visit Plan
        </h2>

        <p class="section-subtitle">
            Add places you would like to visit during your one-day trip.
        </p>

    </div>


    <?php if (empty($_SESSION['trip_plan'])): ?>

        <!-- Empty Plan -->

        <div class="alert alert-info text-center">

            <h5>Your visit plan is empty.</h5>

            <p class="mb-3">
                Explore the available attractions and add places to your plan.
            </p>

            <a
                href="attractions.php"
                class="btn btn-success"
            >
                Explore Places
            </a>

        </div>


    <?php else: ?>

       
        <div class="row g-4">

            <?php

            $ids = array_map('intval', $_SESSION['trip_plan']);

            $id_list = implode(',', $ids);


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
                WHERE attractions.Attraction_ID IN ($id_list)
                ORDER BY attractions.Distance ASC
            ";


            $result = $conn->query($sql);


            if ($result && $result->num_rows > 0):

                while ($place = $result->fetch_assoc()):

            ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="attraction-card">

                            <!-- Image Placeholder -->

                            


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

<div class="attraction-image">
    <?php if ($imageFile !== '' && is_file($imagePath)): ?>

        <img
            src="assets/images/<?php echo htmlspecialchars($imageFile); ?>"
            alt="<?php echo htmlspecialchars($place['Place_Name']); ?>"
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

                                    <?php
                                    echo htmlspecialchars(
                                        $place['Category_Name']
                                    );
                                    ?>

                                </span>


                                <h5 class="card-title">

                                    <?php
                                    echo htmlspecialchars(
                                        $place['Place_Name']
                                    );
                                    ?>

                                </h5>


                                <p>

                                    <strong>
                                        Distance:
                                    </strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $place['Distance']
                                    );
                                    ?>

                                    km

                                </p>


                                <div class="mt-3">

                                    <a
                                        href="attraction_details.php?id=<?php echo $place['Attraction_ID']; ?>"
                                        class="btn btn-explore"
                                    >
                                        View Details
                                    </a>


                                    <a
                                        href="plan.php?action=remove&id=<?php echo $place['Attraction_ID']; ?>"
                                        class="btn btn-outline-danger"
                                    >
                                        Remove
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

            <?php

                endwhile;

            else:

            ?>

                <div class="col-12">

                    <div class="alert alert-warning text-center">

                        The selected attractions could not be found.

                    </div>

                </div>

            <?php endif; ?>

        </div>


        <div class="text-center mt-5">

            <a
                href="attractions.php"
                class="btn btn-success"
            >
                + Add More Places
            </a>

            <a
                href="plan.php?action=clear"
                class="btn btn-outline-danger"
            >
                Clear Plan
            </a>

        </div>

    <?php endif; ?>

</section>


<?php

require_once "includes/footer.php";

?>