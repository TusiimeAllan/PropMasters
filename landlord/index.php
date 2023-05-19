<?php
include_once("../auth/config/connection.php");

session_start();

if (!isset($_SESSION['logged_in'])) {
    header('location:../');
} else {
    if ($_SESSION['user_data']['role'] != 'landlord') {
        header('location:../');
    }
}

// Handle property deletion
if (isset($_GET['delete']) && isset($_GET['property_id'])) {
    $property_id = $_GET['property_id'];

    // Perform the property deletion query
    $delete_query = "DELETE FROM properties WHERE id = '$property_id' AND owner_id = '{$_SESSION['user_data']['id']}'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        $_SESSION['message'] = "Property deleted successfully.";
        $_SESSION['color'] = "green";
    } else {
        $_SESSION['message'] = "Error deleting property: " . mysqli_error($conn);
        $_SESSION['color'] = "red";
    }

    // Redirect to the same page after deletion
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/logo/favicon.ico">

    <title>LandLord | PropMasters</title>

    <!-- Loading Our CSS Code -->
    <link rel="stylesheet" href="./../assets/css/style.css">
    <link rel="stylesheet" href="./../assets/css/bootstrap.min.css">
</head>

<body>

    <div class="header">
        <div class="header-logo">
            <img src="./../assets/images/logo/logo.png" alt="Logo">
        </div>
        <div class="header-links login-container">
            <?php if ($_SESSION['logged_in']) { ?>
                <a href="../auth/logout.php" class="login-link"> Log Out </a>
            <?php } else { ?>
                <a href="../auth/" class="login-link"> Log In </a>
            <?php } ?>
        </div>
    </div>

    <h1 style="margin-top: 150px;">Land Lord Section</h1>
    <h3 style="text-align: center; color: <?php if (isset($_SESSION['color'])) echo $_SESSION['color'];
                                            else echo "white"; ?>;">
        <?php if (isset($_SESSION['message'])) echo $_SESSION['message']; ?>
    </h3>

    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                <a href="add_property.php" class="btn btn-primary">
                    <h3>Register New Property</h3>
                </a>
                <div class="mt-3">
                    <?php
                    $user_id = $_SESSION['user_data']['id'];
                    $property_query = "SELECT * FROM properties WHERE owner_id='$user_id' AND approved=1";
                    $property_result = mysqli_query($conn, $property_query);

                    if (mysqli_num_rows($property_result) == 0) {
                        // No properties found
                        echo "No properties registered to your account yet.";
                    } else {
                        while ($row = mysqli_fetch_assoc($property_result)) {
                            echo '<div class="card mb-3">';
                            echo '<div class="row no-gutters">';
                            echo '<div class="col-md-4">';
                            echo '<img src="' . $row['image'] . '" class="card-img" alt="Property Image">';
                            echo '</div>';
                            echo '<div class="col-md-8">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $row['name'] . '</h5>';
                            echo '<p class="card-text">' . $row['description'] . '</p>';
                            echo '<p class="card-text"><strong>Location: </strong>' . $row['location'] . '</p>';
                            echo '<p class="card-text"><strong>Bedrooms: </strong>' . $row['number_of_bedrooms'] . '</p>';
                            echo '<p class="card-text"><strong>Bathrooms: </strong>' . $row['number_of_bathrooms'] . '</p>';
                            echo '<p class="card-text"><strong>Price: </strong>' . $row['price'] . '</p>';
                            echo '<p class="card-text"><strong>Likes: </strong>' . $row['likes'] . '</p>';
                            echo '<p class="card-text"><strong>Date Posted: </strong>' . $row['date_posted'] . '</p>';
                            echo '<div class="mt-3">';
                            echo '<a href="edit_property.php?property_id=' . $row['id'] . '" class="btn btn-primary mr-2">Edit</a>';
                            echo '<a href="' . $_SERVER['PHP_SELF'] . '?delete=true&property_id=' . $row['id'] . '" class="btn btn-danger">Delete</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>