
<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $place_name = trim($_POST['place_name'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $opening_times = trim($_POST['opening_times'] ?? '');
    $distance = floatval($_POST['distance'] ?? 0);
    $travel_tips = trim($_POST['travel_tips'] ?? '');
    $map_location = trim($_POST['map_location'] ?? '');

    if (
        $place_name === '' ||
        $category_id <= 0 ||
        $description === '' ||
        $opening_times === '' ||
        $distance <= 0 ||
        $travel_tips === '' ||
        $map_location === ''
    ) {

        $error = "Please fill in all fields.";

    } else {

        $stmt = $conn->prepare("
            UPDATE attractions
            SET
                Place_Name = ?,
                Category_ID = ?,
                Description = ?,
                Opening_Times = ?,
                Distance = ?,
                Travel_Tips = ?,
                Map_Location = ?
            WHERE Attraction_ID = ?
        ");

        if ($stmt) {

            $stmt->bind_param(
                "sissdssi",
                $place_name,
                $category_id,
                $description,
                $opening_times,
                $distance,
                $travel_tips,
                $map_location,
                $id
            );

            if ($stmt->execute()) {
                $success = "Attraction updated successfully.";
            } else {
                $error = "Unable to update attraction.";
            }

            $stmt->close();

        } else {
            $error = "Unable to prepare the update query.";
        }
    }
}

$stmt = $conn->prepare("
    SELECT *
    FROM attractions
    WHERE Attraction_ID = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit;
}

$place = $result->fetch_assoc();
$stmt->close();


$categories = $conn->query("
    SELECT Category_ID, Category_Name
    FROM categories
    ORDER BY Category_Name ASC
");


require_once "../includes/header.php";

?>

<section class="page-header">

    <h1>Edit Attraction</h1>

    <p>
        Update attraction information.
    </p>

</section>

<section class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="admin-card">

                
                <?php if ($success): ?>

                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                        <br>
                        Redirecting to the Admin Dashboard...
                    </div>

                    <script>
                       setTimeout(function () {
    window.location.href = "dashboard.php";
}, 2000);
                    </script>

                <?php endif; ?>

                <?php if ($error): ?>

                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Place Name
                        </label>

                        <input
                            type="text"
                            name="place_name"
                            class="form-control"
                            value="<?php echo htmlspecialchars($place['Place_Name']); ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Category
                        </label>

                        <select
                            name="category_id"
                            class="form-select"
                            required
                        >

                            <?php if ($categories): ?>

                                <?php while ($cat = $categories->fetch_assoc()): ?>

                                    <option
                                        value="<?php echo (int) $cat['Category_ID']; ?>"
                                        <?php
                                        if (
                                            (int) $cat['Category_ID'] ===
                                            (int) $place['Category_ID']
                                        ) {
                                            echo 'selected';
                                        }
                                        ?>
                                    >
                                        <?php
                                        echo htmlspecialchars(
                                            $cat['Category_Name']
                                        );
                                        ?>
                                    </option>

                                <?php endwhile; ?>

                            <?php endif; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                            required
                        ><?php echo htmlspecialchars($place['Description']); ?></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Opening Times
                        </label>

                        <input
                            type="text"
                            name="opening_times"
                            class="form-control"
                            value="<?php echo htmlspecialchars($place['Opening_Times']); ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Distance (km)
                        </label>

                        <input
                            type="number"
                            name="distance"
                            class="form-control"
                            step="0.01"
                            min="0.01"
                            value="<?php echo htmlspecialchars((string) $place['Distance']); ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Travel Tips
                        </label>

                        <textarea
                            name="travel_tips"
                            class="form-control"
                            rows="4"
                            required
                        ><?php echo htmlspecialchars($place['Travel_Tips']); ?></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Map Location
                        </label>

                        <input
                            type="text"
                            name="map_location"
                            class="form-control"
                            value="<?php echo htmlspecialchars($place['Map_Location']); ?>"
                            required
                        >

                    </div>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Update Attraction
                    </button>

                    <a
                        href="dashboard.php"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

</section>

<?php

require_once "../includes/footer.php";

?>
