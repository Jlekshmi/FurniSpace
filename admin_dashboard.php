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

        // Check if user_id exists

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

// Fetch all furniture items from the database
$stmt = $pdo->prepare("SELECT * FROM tbl_furniture");
if ($stmt->execute()) {
    $furniture_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Error: " . $stmt->errorInfo()[2];
}

// Handle deletion if a delete request is made
if (isset($_POST['delete'])) {

    $delete_id = $_POST['delete'];
    // Fetch the image path from the database
    $stmt = $pdo->prepare("SELECT furniture_image FROM tbl_furniture WHERE furniture_id = ?");
    $stmt->execute([$delete_id]);
    $image_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $image_path = $image_data['furniture_image'];


    $stmt = $pdo->prepare("DELETE FROM tbl_furniture WHERE furniture_id = ?");
    if ($stmt->execute([$delete_id])) {
        //echo "Deleted successfully";
        header("Location: admin_dashboard.php?user_id=" . $user_id);
        // Delete the image file from the folder
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
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
                            <a class="nav-link active" aria-current="page" href="">Home</a>
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
                <div class="col-6">
                    <h2>Product List</h2>
                </div>
                <div class="col-6 text-end">
                    <a href="manage_furniture.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary btn-sm">Add Furniture</a>
                </div>
            </div>

            <div class="row">
                <?php if (!empty($furniture_items)) { ?>
                    <?php foreach ($furniture_items as $item) { ?>
                        <div class="col-12 col-md-4 product-list mb-3">
                            <div class="card pt-4">
                                <img src="<?php echo ($item['furniture_image']); ?>" alt="<?php echo ($item['furniture_name']); ?>" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate"><?php echo ($item['furniture_name']); ?></h5>
                                    <p class="card-text text-truncate"><?php echo ($item['furniture_description']); ?></p>
                                    <p><b><?php echo ($item['furniture_quantity_available']); ?></b> pieces in stock</p>
                                    <div class="d-flex justify-content between">
                                        <p class="w-100">Price:</p>
                                        <h5 class="price"><?php echo ($item['furniture_price']); ?></h5>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="manage_furniture.php?user_id=<?php echo $item['user_id']; ?>&id=<?php echo $item['furniture_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                            </svg> Edit</a>
                                        <form action="" method="post">
                                            <button class="nav-link" type="submit" value="<?php echo $item['furniture_id']; ?>" name="delete" onclick="return confirm('Are you sure you want to delete this item?');"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                </svg> Delete</button>
                                        </form>

                                    </div>
                                    <div class="mt-4 justify-content">
                                        <small class="d-block text-truncate">Furniture Added By : <?php echo ($item['furniture_added_by']); ?></small>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-12">
                        <p><em>No products listed</em></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</body>

</html>