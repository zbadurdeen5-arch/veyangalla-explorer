`<?php

session_start();

require_once "../config/database.php";


// Check login
if (!isset($_SESSION['admin_id'])) {

    header("Location: login.php");
    exit;
}


require_once "../includes/header.php";


$admin_username = $_SESSION['admin_username'];


$sql = "
    SELECT
        attractions.Attraction_ID,
        attractions.Place_Name,
        attractions.Distance,
        categories.Category_Name
    FROM attractions
    INNER JOIN categories
        ON attractions.Category_ID = categories.Category_ID
    ORDER BY attractions.Attraction_ID ASC
";

$result = $conn->query($sql);

?>

<section class="page-header">

    <h1>Admin Dashboard</h1>

    <p>
        Welcome, <?php echo htmlspecialchars($admin_username); ?>.
    </p>

</section>


<section class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="section-title mb-0">
            Manage Attractions
        </h2>

        <div>

            <a
                href="add_attraction.php"
                class="btn btn-success"
            >
                + Add Attraction
            </a>

            <a
                href="logout.php"
                class="btn btn-outline-danger"
            >
                Logout
            </a>

        </div>

    </div>


    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-success">

                <tr>

                    <th>ID</th>
                    <th>Place Name</th>
                    <th>Category</th>
                    <th>Distance</th>
                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>

                <?php if ($result && $result->num_rows > 0): ?>

                    <?php while ($place = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo $place['Attraction_ID'];
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $place['Place_Name']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $place['Category_Name']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $place['Distance']
                                );
                                ?>
                                km
                            </td>

                            <td>

                                <a
                                    href="edit_attraction.php?id=<?php echo $place['Attraction_ID']; ?>"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <a
                                    href="delete_attraction.php?id=<?php echo $place['Attraction_ID']; ?>"
                                    class="btn btn-sm btn-danger delete-confirm"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5" class="text-center">
                            No attractions found.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>


<?php

require_once "../includes/footer.php";

?>