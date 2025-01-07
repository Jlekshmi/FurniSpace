<?php
include 'config/functions.php';
include 'config/db_connection_pdo.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Clean and retrieve email and password from POST request
    $email = clean_email($_POST['email']);
    $password = clean_password($_POST['password']);

    // Email Validation
    if (isset($_POST['email'])) {
        $form_data['email'] = clean_email($_POST['email']);
        if (empty($form_data['email']) || !validate_email($form_data['email'])) {
            $errors['email'] = "Registered Email Id is required.";
            $form_data['email'] = ''; // Clear invalid value
        }
    } else {
        $form_data['email'] = '';
    }

    // Password Validation
    if (isset($_POST['password'])) {
        $form_data['password'] = clean_password($_POST['password']);
        if (empty($form_data['password']) || !validate_password($form_data['password'])) {
            $errors['password'] = "Registered Password is required.";
            $form_data['password'] = ''; // Clear invalid value
        }
    } else {
        $form_data['password'] = '';
    }

    // If no errors, proceed to check the database
    if (count($errors) == 0) {

        $email = stripslashes($_POST['email']);
        $password = stripslashes($_POST['password']);

        // Prepare and bind SQL statement to select user details by email
        $stmt = $pdo->prepare("SELECT user_id, user_email, user_password FROM tbl_user WHERE user_email = ?");
        $stmt->bindValue(1, $email);

        // Execute the statement
        if ($stmt->execute()) {

            // Fetch the user data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {

                //print_r($row);die;
                // Store the retrieved values
                $user_id = $row['user_id'];
                $tbl_email = $row['user_email'];
                $tbl_password = $row['user_password'];

                // Verify the password
                if ($password === $tbl_password) {
                    // Password is correct, redirect to dashboard with user_id in query string
                    header("Location: admin_dashboard.php?user_id=$user_id");
                    exit();
                } else {
                    $errors['password'] = "Incorrect password.";
                }
            } else {
                $errors['email'] = "No account found with that email.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Class Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet">
</head>


<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/images/logo-light.png" alt="" width="300">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                <div class="d-flex">
                    <a class="btn btn-primary mx-3" href="index.php" type="submit">Login</a>
                    <a class="btn btn-secondary" href="signup.php" type="submit">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="form-signin">
        <form method="post" action="" enctype="multipart/form-data" id="login">
            <h1 class="h3 mb-3 fw-normal">Login</h1>
            <div class="form-floating mb-4">

                <input class="form-control" type="text" id="email" name="email" value="<?php echo isset($form_data['email']) ? $form_data['email'] : ''; ?>">
                <label for="email">Email</label>
                <?php echo display_error('email', $errors); ?>

            </div>


            <div class="form-floating mb-4">

                <input class="form-control" type="password" id="password" name="password" value="<?php echo isset($form_data['password']) ? $form_data['password'] : ''; ?>">
                <label for="password">Password</label>
                <?php echo display_error('password', $errors); ?>

            </div>
            <input class="w-100 btn btn-lg btn-primary" type="submit" name="submit" value="Login">
            <p class="mt-3">Don't have an account yet? <a href="signup.php">Register here</a></p>
        </form>
    </main>
</body>

</html>