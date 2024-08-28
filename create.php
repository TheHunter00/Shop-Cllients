<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";

// Create a new connection to the MySQL database
$connection = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Initialize variables for form data and messages
$name = "";
$email = "";
$phone = "";
$address = "";
$errorMessage = "";
$successMessage = "";

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user input
    $name    = $_POST["name"];
    $email   = $_POST["email"];
    $phone   = $_POST["phone"];
    $address = $_POST["address"];

    do {
        // Check if any field is empty
        if (empty($name) || empty($email) || empty($phone) || empty($address)) {
            $errorMessage = "All the fields are required !!";
            break;
        }

        // Prepare SQL query to check for existing email or phone number
        $sql = "SELECT * FROM clients WHERE email = ? OR phone = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $existingEmail = false;
            $existingPhone = false;

            // Check which fields already exist
            while ($row = $result->fetch_assoc()) {
                if ($row['email'] == $email) {
                    $existingEmail = true;
                }
                if ($row['phone'] == $phone) {
                    $existingPhone = true;
                }
            }

            // Generate an appropriate error message based on the existing fields
            if ($existingEmail && $existingPhone) {
                $errorMessage = "Both email and phone number already exist!";
            } elseif ($existingEmail) {
                $errorMessage = "Email already exists!";
            } elseif ($existingPhone) {
                $errorMessage = "Phone number already exists!";
            }
            break;
        }

        // Prepare and execute SQL query to insert a new client
        $sql = "INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $address);
        $result = $stmt->execute();

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        // Set success message
        $successMessage = "Client added successfully!";

        // Clear form fields
        $name = "";
        $email = "";
        $phone = "";
        $address = "";

    } while (false);
}

// Close the database connection
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Include Bootstrap JS for interactive components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function redirectAfterDelay(url, delay) {
            setTimeout(function() {
                window.location.href = url;
            }, delay);
        }
    </script>
</head>

<body>
    <div class="container my-5">
        <h2>Add New Client</h2>
        <hr>
        <?php
        // Display error message if any
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                 <strong>$errorMessage</strong>
                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>
        <?php
        // Display success message if any
        if (!empty($successMessage)) {
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                 <strong>$successMessage</strong>
                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            <script>
                redirectAfterDelay('/projects/myshop/index.php', 2000); // Redirect after 5 seconds
            </script>
            ";
        }
        ?>
        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name :</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Name">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email :</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone :</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Phone">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address :</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Address">
                </div>
            </div>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/projects/myshop/index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
