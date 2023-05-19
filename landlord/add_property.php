<?php
session_start();

include_once("../auth/config/connection.php");

if (!isset($_SESSION['logged_in'])) {
    header('location:../');
} else {
    if ($_SESSION['user_data']['role'] != 'landlord') {
        header('location:../');
    }
}

if (isset($_POST["property-submit"])) {
    // New property submission

    $user_id = $_SESSION["user_data"]['id'];
    $name = $_POST["name"];
    $location = $_POST["location"];
    $description = $_POST["description"];
    $bedrooms = $_POST["bedrooms"];
    $bathrooms = $_POST["bathrooms"];
    $cost = $_POST["price"];
    $property_type = $_POST["property_type"];

    // File upload handling
    $targetDirectory = "./../uploads/"; // Directory where the images will be stored
    $targetFile = $targetDirectory . basename($_FILES["image"]["name"]); // Path of the uploaded file
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); // File extension

    // Check if the file is a valid image
    $validExtensions = array("jpg", "jpeg", "png", "gif");
    if (in_array($imageFileType, $validExtensions)) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // File upload successful, save the image path to the database
            $imagePath = $targetFile;

            $insert_query = "INSERT INTO properties (name, image, location, description, number_of_bedrooms, number_of_bathrooms, type, price, owner_id) 
            VALUES ('$name', '$imagePath', '$location', '$description', '$bedrooms', '$bathrooms', '$property_type', '$cost', '$user_id')";

            if (mysqli_query($conn, $insert_query)) {
                $_SESSION['message'] = "New property record inserted successfully.";
                $_SESSION['color'] = "green";
                header("Location: index.php");
            } else {
                echo "Error inserting property record: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "Invalid file format. Only JPG, JPEG, PNG, and GIF images are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/logo/favicon.ico">

    <title>Add Property | PropMasters</title>

    <!-- Loading Our CSS Code -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>

    <div class="header">
        <div class="header-logo">
            <img src="./../assets/images/logo/logo.png" alt="Logo">
        </div>
        <div class="header-links">
            <a href="index.php">View My Properties</a>
        </div>
        <div class="header-links login-container">
            <?php if ($_SESSION['logged_in']) { ?>
                <a href="../auth/logout.php" class="login-link"> Log Out </a>
            <?php } else { ?>
                <a href="../auth/" class="login-link"> Log In </a>
            <?php } ?>
        </div>
    </div>

    <h1 style="margin-top: 150px;">Add New Property</h1>
    <div class="container" style="width: 60%;">
        <form method="POST" action="add_property.php" class="add-property-form" id="add-property-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" name="image" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter property name" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" name="location" placeholder="Enter property location" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" name="description" placeholder="Enter property description" required>
            </div>
            <div class="form-group">
                <label for="bedrooms">Number of Bedrooms</label>
                <input type="number" class="form-control" name="bedrooms" placeholder="Enter number of bedrooms" required>
            </div>
            <div class="form-group">
                <label for="bathrooms">Number of Bathrooms</label>
                <input type="number" class="form-control" name="bathrooms" placeholder="Enter number of bathrooms" required>
            </div>
            <div class="form-group">
                <label for="property_type">Property Type</label>
                <select class="form-control" name="property_type">
                    <option value="house">House</option>
                    <option value="apartment">Apartment</option>
                    <option value="condominium">Condominium</option>
                    <option value="townhouse">Town House</option>
                    <option value="villa">Villa</option>
                    <option value="duplex">Duplex</option>
                    <option value="studio">Studio</option>
                    <option value="bungalow">Bungalow</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" name="price" placeholder="Enter property price in USD" required>
            </div>
            <input type="submit" class="btn btn-primary" name="property-submit" value="Add Property">
        </form>
    </div>

</body>

</html>