<?php

require_once "config/database.php";
require_once "includes/header.php";


$message_sent = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');


    if (
        $name !== '' &&
        $email !== '' &&
        $subject !== '' &&
        $message !== ''
    ) {

        $message_sent = true;
    }
}

?>

<section class="page-header">

    <h1>Contact Us</h1>

    <p>
        Have a question or suggestion? Send us a message.
    </p>

</section>


<section class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <?php if ($message_sent): ?>

                <div class="alert alert-success">

                    Thank you for contacting Veyangalla Explorer.
                    Your message has been received.

                </div>

            <?php endif; ?>


            <div class="contact-form">

                <h2 class="section-title text-center">
                    Send Us a Message
                </h2>

                <p class="text-center text-muted mb-4">
                    Fill in the form below.
                </p>


                <form method="POST" action="contact.php">

                    <div class="mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Subject
                        </label>

                        <input
                            type="text"
                            name="subject"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Message
                        </label>

                        <textarea
                            name="message"
                            rows="6"
                            class="form-control"
                            required
                        ></textarea>

                    </div>


                    <div class="text-center">

                        <button
                            type="submit"
                            class="btn btn-success px-5"
                        >
                            Send Message
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>


<?php

require_once "includes/footer.php";

?>