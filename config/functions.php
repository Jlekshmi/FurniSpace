<?php
// Function to clean general input, removing all characters except letters, numbers, spaces, periods, and hyphens
function clean_input($data)
{
    return preg_replace('/[^a-zA-Z0-9.\s\-]/', '', trim($data));
}

// Function to clean email input, using PHP's filter_var to sanitize the email
function clean_email($email)
{
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

// Function to validate that the text input contains only letters, apostrophes, hyphens, and spaces
function validate_text($text)
{
    return preg_match("/^[a-zA-Z-' ]*$/", $text);
}

// Function to validate the email format
function validate_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
    //return preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email);
}

// Function to validate that the phone number contains exactly 10 digits
function validate_phone($phone)
{
    return preg_match("/^[0-9]{10}$/", $phone);
}

// Function to validate that the date of birth indicates the user is at least 18 years old
function validate_age($dob)
{
    $birthdate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthdate)->y;
    return $age >= 18;
}

// Function to calculate the age based on the date of birth
function calculate_age($dob)
{
    if ($dob) {
        $birthdate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($birthdate)->y;
        return $age;
    }
    return '';
}

// Function to clean password input
function clean_password($password)
{
    return trim($password); // Only trim spaces from the beginning and end
}
// Function to validate Password
function validate_password($password)
{

    return preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password);
}

// Function to validate the postal code format (Canadian postal code format: A1A1A1)
function validate_postal_code($postal_code)
{

    return preg_match("/^[A-Za-z]\d[A-Za-z]?\d[A-Za-z]\d$/", $postal_code);
}

// Function to validate the street address, allowing numbers, letters, spaces, periods, and hyphens
function validate_street($street)
{
    // Street address can include numbers, letters, spaces, periods, and dashes
    return preg_match("/^[a-zA-Z0-9\s\.\-]+$/", $street);
}

// Function to validate Furniture Quantity
function validate_number($quantity)
{
    return preg_match("/^[0-9]+$/", $quantity); // Allows only numbers
}

// Function to validate Furniture Price
function validate_price($price)
{
    return preg_match("/^\d+(\.\d{1,2})?$/", $price); // Allows numbers with up to two decimal places
}

// Function to validate Image
function validate_image($file)
{
    $errors = [];
    $extensions = ["jpeg", "jpg", "png"];
    $max_size = 2097152; // 2MB

    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $extensions)) {
        $errors[] = "Invalid file type. Only JPEG, JPG, and PNG are allowed.";
    }

    if ($file_size > $max_size) {
        echo $file_size;
        $errors[] = "File size must be less than 2MB.";
    }

    if (empty($errors)) {
        $upload_dir = './assets/images/image-upload/';
        $upload_file = $upload_dir . basename($file_name);

        if (move_uploaded_file($file_tmp, $upload_file)) {
            return [true, $upload_file];
        } else {
            $errors[] = "Failed to upload file.";
        }
    }

    return [false, $errors];
}

// Function to display validation errors, if any, for a given field
function display_error($field, $errors)
{
    if (isset($errors[$field])) {
        return "<div class='invalid-feedback'>{$errors[$field]}</div>";
    }
    return '';
}
