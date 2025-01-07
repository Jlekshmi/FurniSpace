<?php
include 'config/functions.php';
include 'config/db_connection_pdo.php';

$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // First Name Validation
    if (isset($_POST['firstname'])) {
        $form_data['firstname'] = clean_input($_POST['firstname']);
        if (empty($form_data['firstname']) || !validate_text($form_data['firstname'])) {
            $errors['firstname'] = "First Name is required and should contain only letters.";
            $form_data['firstname'] = ''; // Clear invalid value
        }
    } else {
        $form_data['firstname'] = '';
    }

    // Last Name Validation
    if (isset($_POST['lastname'])) {
        $form_data['lastname'] = clean_input($_POST['lastname']);
        if (empty($form_data['lastname']) || !validate_text($form_data['lastname'])) {
            $errors['lastname'] = "Last Name is required and should contain only letters.";
            $form_data['lastname'] = ''; // Clear invalid value
        }
    } else {
        $form_data['lastname'] = '';
    }

    // Gender Validation
    if (isset($_POST['gender'])) {
        $form_data['gender'] = clean_input($_POST['gender']);
        if (empty($form_data['gender'])) {
            $errors['gender'] = "Gender is required.";
            $form_data['gender'] = ''; // Clear invalid value
        }
    } else {
        $errors['gender'] = "Gender is required.";
        $form_data['gender'] = '';
    }

    // Date of Birth Validation
    if (isset($_POST['dob'])) {
        $form_data['dob'] = clean_input($_POST['dob']);
        if (empty($form_data['dob']) || !validate_age($form_data['dob'])) {
            $errors['dob'] = "Date of Birth is required and you must be at least 18 years old.";
            $form_data['dob'] = ''; // Clear invalid value
        } else {
            $form_data['age'] = calculate_age($form_data['dob']);
        }
    } else {
        $form_data['dob'] = '';
    }

    // Email Validation
    if (isset($_POST['email'])) {
        $form_data['email'] = clean_email($_POST['email']);
        if (empty($form_data['email']) || !validate_email($form_data['email'])) {
            $errors['email'] = "Valid Email is required.";
            $form_data['email'] = ''; // Clear invalid value
        }
    } else {
        $form_data['email'] = '';
    }

    // Phone Validation
    if (isset($_POST['phone'])) {
        $form_data['phone'] = clean_input($_POST['phone']);
        if (empty($form_data['phone']) || !validate_phone($form_data['phone'])) {
            $errors['phone'] = "Phone Number is required and should be in valid format (10 Digits).";
            $form_data['phone'] = ''; // Clear invalid value
        }
    } else {
        $form_data['phone'] = '';
    }

    // Address Validation
    if (isset($_POST['street'])) {
        $form_data['street'] = clean_input($_POST['street']);
        if (empty($form_data['street']) || !validate_street($form_data['street'])) {
            $errors['street'] = "Street Address is required and should be valid.";
            $form_data['street'] = ''; // Clear invalid value
        }
    } else {
        $form_data['street'] = '';
    }

    if (isset($_POST['city'])) {
        $form_data['city'] = clean_input($_POST['city']);
        if (empty($form_data['city']) || !validate_text($form_data['city'])) {
            $errors['city'] = "City is required and should contain only letters.";
            $form_data['city'] = ''; // Clear invalid value
        }
    } else {
        $form_data['city'] = '';
    }

    // Postal Code Validation
    if (isset($_POST['postal_code'])) {
        $form_data['postal_code'] = clean_input($_POST['postal_code']);
        if (empty($form_data['postal_code']) || !validate_postal_code($form_data['postal_code'])) {
            $errors['postal_code'] = "Postal Code is required and should be in valid format.";
            $form_data['postal_code'] = ''; // Clear invalid value
        }
    } else {
        $form_data['postal_code'] = '';
    }

    // Password Validation
    if (isset($_POST['password'])) {
        $form_data['password'] = clean_password($_POST['password']);
        if (empty($form_data['password']) || !validate_password($form_data['password'])) {
            $errors['password'] = "Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.";
            $form_data['password'] = ''; // Clear invalid value
        }
    } else {
        $form_data['password'] = '';
    }

    if (count($errors) == 0) {

        // Clean and retrieve form data

        $firstname = stripslashes($_POST['firstname']);
        $lastname = stripslashes($_POST['lastname']);
        $gender = stripslashes($_POST['gender']);
        $dob = stripslashes($_POST['dob']);
        $phone = stripslashes($_POST['phone']);
        $address = stripslashes($_POST['street'] . "," . $_POST['city'] . "," . $_POST['state'] . "," . $_POST['postal_code'] . "," . $_POST['country']);
        $email = stripslashes($_POST['email']);
        $password = stripslashes($_POST['password']);

        // Prepare and bind SQL statement to insert user data
        $stmt = $pdo->prepare("INSERT INTO tbl_user (user_firstname, user_lastname, user_gender, user_dob, user_phone, user_address, user_email, user_password)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $firstname);
        $stmt->bindValue(2, $lastname);
        $stmt->bindValue(3, $gender);
        $stmt->bindValue(4, $dob);
        $stmt->bindValue(5, $phone);
        $stmt->bindValue(6, $address);
        $stmt->bindValue(7, $email);
        $stmt->bindValue(8, $password);


        if ($stmt->execute()) {
            echo "success";
            header("Location: index.php");
            exit;
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
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
    <main class="form-register">
        <div class="container">
            <img class="mb-4 " src="assets/logo-icon.png" alt="" width="72">
            <h1 class="h3 mb-3 fw-normal">Register</h1>
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="" enctype="multipart/form-data" id="register">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Personal Details
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" type="text" id="firstname" name="firstname" value="<?php echo isset($form_data['firstname']) ? $form_data['firstname'] : ''; ?>">
                                    <label class="form-label" for="firstname">First Name</label>
                                    <?php echo display_error('firstname', $errors); ?>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" type="text" id="lastname" name="lastname" value="<?php echo isset($form_data['lastname']) ? $form_data['lastname'] : ''; ?>">
                                    <label class="form-label" for="lastname">Last Name</label>
                                    <?php echo display_error('lastname', $errors); ?>
                                </div>

                            </div>

                            <div class="col-md-6 mb-3">
                                Gender
                                <div class="d-flex">


                                    <input class="form-check-input mx-2" type="radio" id="female" name="gender" value="Female" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Female') echo 'checked'; ?>>
                                    <label class="form-check-label " for="female">Female</label>



                                    <input class="form-check-input mx-2" type="radio" id="male" name="gender" value="Male" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'Male') echo 'checked'; ?>>
                                    <label class="form-check-label " for="male">Male</label>


                                </div>
                                <?php echo display_error('gender', $errors); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" type="date" id="dob" name="dob" value="<?php echo isset($form_data['dob']) ? $form_data['dob'] : ''; ?>">
                                    <label class="form-label" for="dob">Date Of Birth</label>
                                    <?php echo display_error('dob', $errors); ?>
                                </div>
                            </div>


                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" type="text" id="phone" name="phone" value="<?php echo isset($form_data['phone']) ? $form_data['phone'] : ''; ?>">
                                    <label class="form-label" for="phone">Phone</label>
                                    <?php echo display_error('phone', $errors); ?>
                                </div>
                            </div>

                            <input class="form-control" type="hidden" id="age" name="age" value="<?php echo isset($form_data['age']) ? $form_data['age'] : ''; ?>">
                            <?php echo display_error('age', $errors); ?>


                            <div class="col-12">
                                <h5 class="mb-3">Address
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" type="text" id="street" name="street" value="<?php echo isset($form_data['street']) ? $form_data['street'] : ''; ?>">
                                    <label class="form-label" for="street">Street Address</label>
                                    <?php echo display_error('street', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" type="text" id="city" name="city" value="<?php echo isset($form_data['city']) ? $form_data['city'] : ''; ?>">
                                    <label class="form-label" for="city">City</label>
                                    <?php echo display_error('city', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <select name="state" id="state" class="form-select">
                                        <option value="AB" <?php if (isset($_POST['state']) && $_POST['state'] == 'AB') echo 'selected'; ?>>Alberta</option>
                                        <option value="BC" <?php if (isset($_POST['state']) && $_POST['state'] == 'BC') echo 'selected'; ?>>British Columbia</option>
                                        <option value="MB" <?php if (isset($_POST['state']) && $_POST['state'] == 'MB') echo 'selected'; ?>>Manitoba</option>
                                        <option value="NB" <?php if (isset($_POST['state']) && $_POST['state'] == 'NB') echo 'selected'; ?>>New Brunswick</option>
                                        <option value="NL" <?php if (isset($_POST['state']) && $_POST['state'] == 'NL') echo 'selected'; ?>>Newfoundland and Labrador</option>
                                        <option value="NS" <?php if (isset($_POST['state']) && $_POST['state'] == 'NS') echo 'selected'; ?>>Nova Scotia</option>
                                        <option value="NT" <?php if (isset($_POST['state']) && $_POST['state'] == 'NT') echo 'selected'; ?>>Northwest Territories</option>
                                        <option value="NU" <?php if (isset($_POST['state']) && $_POST['state'] == 'NU') echo 'selected'; ?>>Nunavut</option>
                                        <option value="ON" <?php if (isset($_POST['state']) && $_POST['state'] == 'ON') echo 'selected'; ?>>Ontario</option>
                                        <option value="PE" <?php if (isset($_POST['state']) && $_POST['state'] == 'PE') echo 'selected'; ?>>Prince Edward Island</option>
                                        <option value="QC" <?php if (isset($_POST['state']) && $_POST['state'] == 'QC') echo 'selected'; ?>>Quebec</option>
                                        <option value="SK" <?php if (isset($_POST['state']) && $_POST['state'] == 'SK') echo 'selected'; ?>>Saskatchewan</option>
                                        <option value="YT" <?php if (isset($_POST['state']) && $_POST['state'] == 'YT') echo 'selected'; ?>>Yukon</option>
                                    </select>
                                    <label class="form-label" for="state">State / Province</label>
                                    <?php echo display_error('state', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" placeholder="A1A1A1" type="text" id="postal_code" name="postal_code" value="<?php echo isset($form_data['postal_code']) ? $form_data['postal_code'] : ''; ?>">
                                    <label class="form-label" for="postal_code">Postal / Zip Code</label>
                                    <?php echo display_error('postal_code', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" type="text" id="country" name="country" value="Canada" disabled>
                                    <label class="form-label" for="country">Country</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <h5 class="mb-3">Login Details
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" placeholder="email@domain.com" type="text" id="email" name="email" value="<?php echo isset($form_data['email']) ? $form_data['email'] : ''; ?>">
                                    <label class="form-label" for="email">Email</label>
                                    <?php echo display_error('email', $errors); ?>
                                </div>

                            </div>


                            <div class="col-md-6 mb-3">
                                <div class="form-floating mb-4">

                                    <input class="form-control" placeholder="Passw0rd!" type="password" id="password" name="password" value="<?php echo isset($form_data['password']) ? $form_data['password'] : ''; ?>">
                                    <label class="form-label" for="password">Password</label>
                                    <?php echo display_error('password', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 text-center">
                                <input class="w-50 btn btn-primary btn-lg" type="submit" name="submit" value="Register">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>