<?php

session_start();

require_once "../config/database.php";


$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';


    if ($username === '' || $password === '') {

        $error = "Please enter your username and password.";

    } else {

        $username_safe = $conn->real_escape_string($username);

        $sql = "
            SELECT Admin_ID, username, password
            FROM administrators
            WHERE username = '$username_safe'
            LIMIT 1
        ";

        $result = $conn->query($sql);


        if ($result && $result->num_rows === 1) {

            $admin = $result->fetch_assoc();


            $valid_password = false;


            if (
                password_verify(
                    $password,
                    $admin['password']
                )
            ) {

                $valid_password = true;

            } elseif (
                hash_equals(
                    (string) $admin['password'],
                    (string) $password
                )
            ) {

                $valid_password = true;
            }


            if ($valid_password) {

                $_SESSION['admin_id'] = $admin['Admin_ID'];
                $_SESSION['admin_username'] = $admin['username'];

                header("Location: dashboard.php");
                exit;

            } else {

                $error = "Invalid username or password.";
            }

        } else {

            $error = "Invalid username or password.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login - Veyangalla Explorer</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="http://localhost/veyangalla_explorer/assets/css/style.css"
    >

</head>


<body>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="admin-card">

                <h2 class="text-center text-success mb-4">
                    Admin Login
                </h2>


                <?php if ($error !== ''): ?>

                    <div class="alert alert-danger">

                        <?php
                        echo htmlspecialchars($error);
                        ?>

                    </div>

                <?php endif; ?>


                <form method="POST" action="login.php">

                    <div class="mb-3">

                        <label class="form-label">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn btn-success w-100"
                    >
                        Login
                    </button>

                </form>


                <div class="text-center mt-3">

                    <a
                        href="../index.php"
                        class="text-decoration-none"
                    >
                        ← Back to Website
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


</body>

</html>