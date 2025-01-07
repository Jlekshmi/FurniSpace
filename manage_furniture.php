<?php
include 'config/functions.php';
include 'config/db_connection_pdo.php';

$errors = [];
$form_data = [];

$user_id = $_GET['user_id'];
$furniture_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch the user details for the 'added by' field
if ($user_id) {
    $stmt = $pdo->prepare("SELECT user_id, user_firstname, user_lastname FROM tbl_user WHERE user_id = ?");
    $stmt->bindValue(1, $user_id);

    // Execute the statement
    if ($stmt->execute()) {

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $form_data['user_id'] = $row['user_id'];
            $form_data['firstname'] = $row['user_firstname'];
            $form_data['lastname'] = $row['user_lastname'];
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }
}

// Fetch the furniture item to edit
if ($furniture_id) {
    $stmt = $pdo->prepare("SELECT * FROM tbl_furniture WHERE furniture_id = ?");
    $stmt->execute([$furniture_id]);
    $furniture_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($furniture_item) {
        $form_data = array_merge($form_data, $furniture_item);
    } else {
        echo "No furniture item found with that ID.";
        exit;
    }
}


if (isset($_POST['submit'])) {

    // Furniture Name Validation
    if (isset($_POST['furniture_name'])) {
        $form_data['furniture_name'] = clean_input($_POST['furniture_name']);
        if (empty($form_data['furniture_name']) || !validate_text($form_data['furniture_name'])) {
            $errors['furniture_name'] = "Furniture Name is required and should contain only letters.";
            $form_data['furniture_name'] = ''; // Clear invalid value
        }
    } else {
        $form_data['furniture_name'] = '';
    }

    // Furniture Description Validation
    if (isset($_POST['furniture_description'])) {
        $form_data['furniture_description'] = clean_input($_POST['furniture_description']);
        if (empty($form_data['furniture_description'])) {
            $errors['furniture_description'] = "Furniture Description is required.";
            $form_data['furniture_description'] = ''; // Clear invalid value
        }
    } else {
        $form_data['furniture_description'] = '';
    }
    // Furniture Quantity Available Validation
    if (isset($_POST['furniture_quantity_available'])) {
        $form_data['furniture_quantity_available'] = clean_input($_POST['furniture_quantity_available']);
        if (empty($form_data['furniture_quantity_available']) || !validate_number($form_data['furniture_quantity_available'])) {
            $errors['furniture_quantity_available'] = "Furniture Quantity is required and should be a valid number.";
            $form_data['furniture_quantity_available'] = ''; // Clear invalid value
        }
    } else {
        $form_data['furniture_quantity_available'] = '';
    }

    // Furniture Price Validation
    if (isset($_POST['furniture_price'])) {
        $form_data['furniture_price'] = clean_input($_POST['furniture_price']);
        if (empty($form_data['furniture_price']) || !validate_price($form_data['furniture_price'])) {
            $errors['furniture_price'] = "Furniture Price is required and Allows numbers with up to two decimal places.";
            $form_data['furniture_price'] = ''; // Clear invalid value
        }
    } else {
        $form_data['furniture_price'] = '';
    }
    // Furniture Image Validation
    if (isset($_FILES['furniture_image']) && $_FILES['furniture_image']['error'] == UPLOAD_ERR_OK) {
        list($valid, $result) = validate_image($_FILES['furniture_image']);
        if ($valid) {
            $image_path = $result;
        } else {
            $errors['furniture_image'] = implode(", ", $result);
        }
    } else {
        // If no new image is uploaded, keep the old image path
        if (isset($form_data['furniture_image'])) {
            $image_path = $form_data['furniture_image'];
        } else {
            $errors['furniture_image'] = "Furniture Image is required.";
        }
    }

    if (count($errors) == 0) {

        $furniture_added_by = ($_POST['furniture_added_by']);
        if ($furniture_id) {
            // Update existing furniture item
            $stmt = $pdo->prepare("UPDATE tbl_furniture SET user_id = ?, furniture_name = ?, furniture_description = ?, furniture_quantity_available = ?, furniture_price = ?, furniture_image = ?, furniture_added_by = ? WHERE furniture_id = ?");
            $stmt->execute([$user_id, $form_data['furniture_name'], $form_data['furniture_description'], $form_data['furniture_quantity_available'], $form_data['furniture_price'], $image_path, $form_data['furniture_added_by'], $furniture_id]);
            header("Location: admin_dashboard.php?user_id=" . $user_id);
        } else {

            $stmt = $pdo->prepare("INSERT INTO tbl_furniture (user_id, furniture_name, furniture_description, furniture_quantity_available, furniture_price, furniture_image, furniture_added_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $user_id);
            $stmt->bindValue(2, $form_data['furniture_name']);
            $stmt->bindValue(3, $form_data['furniture_description']);
            $stmt->bindValue(4, $form_data['furniture_quantity_available']);
            $stmt->bindValue(5, $form_data['furniture_price']);
            $stmt->bindValue(6, $image_path);
            $stmt->bindValue(7, $furniture_added_by);
            // Execute the statement
            if ($stmt->execute()) {
                //echo "Added successfully";
                header("Location: admin_dashboard.php?user_id=" . $user_id);
                exit;
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
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
    <title>FurniSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php?user_id=<?php echo $user_id; ?>">
                <img src="assets/images/logo-light.png" alt="" width="300">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                <div class="d-flex">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="admin_dashboard.php?user_id=<?php echo $user_id; ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="dashboard">
            <div class="row mt-5 mb-3">
                <div class="col-12">
                    <h5><?php echo $furniture_id ? 'Edit' : 'Add'; ?> Furniture</h5>

                </div>
                <div class="col-12">
                    <form method="post" action=" " enctype="multipart/form-data" id="admin_dashboard">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-floating ">
                                    <input class="form-control" placeholder="Eg:Corner sofa-bed with storage" type="text" id="furniture_name" name="furniture_name" value="<?php echo isset($form_data['furniture_name']) ? $form_data['furniture_name'] : ''; ?>">
                                    <label class="form-label" for="furniture_name">Furniture Name</label>
                                    <?php echo display_error('furniture_name', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-floating ">
                                    <textarea class="form-control" id="furniture_description" name="furniture_description" placeholder="Eg:This sofa converts quickly and easily into a spacious bed when you remove the back cushions and pull out the underframe." rows="10"><?php echo isset($form_data['furniture_description']) ? $form_data['furniture_description'] : ''; ?></textarea>
                                    <label class="form-label" for="furniture_description">Furniture Description</label>
                                    <?php echo display_error('furniture_description', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating ">
                                    <input class="form-control" placeholder="Enter a valid quantity" type="text" id="furniture_quantity_available" name="furniture_quantity_available" value="<?php echo isset($form_data['furniture_quantity_available']) ? $form_data['furniture_quantity_available'] : ''; ?>">
                                    <label class="form-label" for="furniture_quantity_available">Furniture Quantity Available</label>
                                    <?php echo display_error('furniture_quantity_available', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating ">
                                    <input class="form-control" placeholder="Eg:500.20" type="text" id="furniture_price" name="furniture_price" value="<?php echo isset($form_data['furniture_price']) ? $form_data['furniture_price'] : ''; ?>">
                                    <label class="form-label" for="furniture_price">Furniture Price ($)</label>
                                    <?php echo display_error('furniture_price', $errors); ?>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <?php if (isset($form_data['furniture_image']) && $furniture_id) { ?>
                                    <h5 class=" mb-3">Existing image of the product</h5>
                                    <img src="<?php echo isset($form_data['furniture_image']) ? $form_data['furniture_image'] : ''; ?>" class="card-img-top  mb-3">
                                    <h5 class=" mb-3">Choose image if you wish to replace</h5>
                                <?php } ?>
                                <div class="form-floating ">
                                    <input class="form-control" type="file" id="furniture_image" name="furniture_image" value="<?php echo isset($form_data['furniture_image']) ? $form_data['furniture_image'] : ''; ?>">
                                    <label class="form-label" for="furniture_image">Furniture Image</label>
                                    <?php echo display_error('furniture_image', $errors); ?>
                                </div>
                            </div>
                            <div>
                                <input class="form-control" type="hidden" id="furniture_added_by" name="furniture_added_by" value="<?php echo isset($form_data['user_id']) ? $form_data['firstname'] . " " . $form_data['lastname'] : ''; ?>">
                            </div>
                            <div class="d-flex col-md-12 mb-3 justify-content-center">
                                <a href="admin_dashboard.php?user_id=<?php echo $user_id; ?>" class="btn btn-secondary">Cancel</a>
                                <button class="btn btn-primary mx-3" name="submit" id="submit"><?php echo $furniture_id ? 'Update' : 'Add'; ?> Furniture</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>