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

// Check if a property ID is provided in the query string
if (isset($_GET['property_id'])) {
    $propertyId = $_GET['property_id'];

    // Fetch property details from the database
    $select_query = "SELECT * FROM properties WHERE id = $propertyId";
    $result = mysqli_query($conn, $select_query);
    $property = mysqli_fetch_assoc($result);

    // Check if the property exists and belongs to the logged-in user
    if ($property && $property['owner_id'] == $_SESSION["user_data"]["id"]) {
        // Assign the fetched values to variables
        $name = $property["name"];
        $location = $property["location"];
        $description = $property["description"];
        $bedrooms = $property["number_of_bedrooms"];
        $bathrooms = $property["number_of_bathrooms"];
        $cost = $property["price"];
        $property_type = $property["type"];
        $imagePath = $property["image"];
    } else {
        // Property not found or does not belong to the user
        $_SESSION['message'] = "Property not found or does not belong to the user";
        $_SESSION['color'] = "red";
        header("Location: index.php");
        exit();
    }
}

if (isset($_POST["property-submit"])) {
    // Edit property submission

    $propertyId = $_POST["property_id"];
    $name = $_POST["name"];
    $location = $_POST["location"];
    $description = $_POST["description"];
    $bedrooms = $_POST["bedrooms"];
    $bathrooms = $_POST["bathrooms"];
    $cost = $_POST["price"];
    $property_type = $_POST["property_type"];

    // Check if a file is selected for upload
    if (!empty($_FILES["image"]["name"])) {
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
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "Invalid file format. Only JPG, JPEG, PNG, and GIF images are allowed.";
        }
    }

    $update_query = "UPDATE properties SET name='$name', location='$location', description='$description', number_of_bedrooms='$bedrooms', number_of_bathrooms='$bathrooms', type='$property_type', price='$cost'";

    // Include the image path in the update query if a file was uploaded
    if (!empty($imagePath)) {
        $update_query .= ", image='$imagePath'";
    }

    $update_query .= " WHERE id=$propertyId";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = "Property updated successfully.";
        $_SESSION['color'] = "green";
        header("Location: index.php");
    } else {
        echo "Error updating property: " . mysqli_error($conn);
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

    <title>Edit Property | PropMasters</title>

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

    <h1 style="margin-top: 150px;">Edit Property</h1>

    <div class="container" style="width: 60%;">
        <form method="POST" action="edit_property.php" class="add-property-form" id="add-property-form" enctype="multipart/form-data">
            <!-- Existing code for form structure -->

            <!-- Pre-fill form fields with fetched data -->
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" name="image">
                <?php if (!empty($imagePath)) : ?>
                    <img src="<?php echo $imagePath; ?>" alt="Current Image" style="width: 200px; height: 200px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter property name" value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" name="location" placeholder="Enter property location" value="<?php echo $location; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" name="description" placeholder="Enter property description" value="<?php echo $description; ?>" required>
            </div>
            <div class="form-group">
                <label for="bedrooms">Number of Bedrooms</label>
                <input type="number" class="form-control" name="bedrooms" placeholder="Enter number of bedrooms" value="<?php echo $bedrooms; ?>" required>
            </div>
            <div class="form-group">
                <label for="bathrooms">Number of Bathrooms</label>
                <input type="number" class="form-control" name="bathrooms" placeholder="Enter number of bathrooms" value="<?php echo $bathrooms; ?>" required>
            </div>
            <div class="form-group">
                <label for="property_type">Property Type</label>
                <select class="form-control" name="property_type">
                    <option value="house" <?php echo ($property_type == 'house') ? 'selected' : ''; ?>>House</option>
                    <option value="apartment" <?php echo ($property_type == 'apartment') ? 'selected' : ''; ?>>Apartment</option>
                    <option value="condominium" <?php echo ($property_type == 'condominium') ? 'selected' : ''; ?>>Condominium</option>
                    <option value="townhouse" <?php echo ($property_type == 'townhouse') ? 'selected' : ''; ?>>Town House</option>
                    <option value="villa" <?php echo ($property_type == 'villa') ? 'selected' : ''; ?>>Villa</option>
                    <option value="duplex" <?php echo ($property_type == 'duplex') ? 'selected' : ''; ?>>Duplex</option>
                    <option value="studio" <?php echo ($property_type == 'studio') ? 'selected' : ''; ?>>Studio</option>
                    <option value="bungalow" <?php echo ($property_type == 'bungalow') ? 'selected' : ''; ?>>Bungalow</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" name="price" placeholder="Enter property price in USD" value="<?php echo $cost; ?>" required>
            </div>

            <!-- Existing hidden input field -->
            <input type="hidden" name="property_id" value="<?php echo $_GET['property_id']; ?>">

            <input type="submit" class="btn btn-primary" name="property-submit" value="Edit Property">
        </form>
    </div>

    <!-- Additional HTML content -->

</body>

</html>