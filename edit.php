<?php 
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";

// Create a new connection to the MySQL database
$connection = new mysqli($servername, $username, $password, $database);

$id="";
$name = "";
$email = "";
$phone = "";
$address = "";

$errorMessage = "";
$successMessage = "";

// Process the GET request
if($_SERVER['REQUEST_METHOD']=='GET'){
    //GET method : show the data of the client
    if (!isset($_GET["id"])){
        header("location: /projects/myshop/index.php");
        exit;
    }

    $id = $_GET["id"];

    // Read the row of the selected client from database table
    $sql = "SELECT * FROM clients WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row){
        header("location: /projects/myshop/index.php");
        exit;
    }

    $name=$row["name"];
    $email=$row["email"];
    $phone=$row["phone"];
    $address=$row["address"];
}

// Process the POST request
else {
    //POST method : update the data of the client
    $id=$_POST["id"];
    $name=$_POST["name"];
    $email=$_POST["email"];
    $phone=$_POST["phone"];
    $address=$_POST["address"];

    do {
        if (empty($name) || empty($email) || empty($phone) || empty($address)) {
            $errorMessage = "All the fields are required !!";
            break;
        }
        
        // Prepare and execute the update query
        $sql="UPDATE clients SET name=?, email=?, phone=?, address=? WHERE id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
        $result = $stmt->execute();

        if(!$result){
            $errorMessage="Invalid query: ". $connection->error;
            break;
        }

        $successMessage = "Client Information Updated Successfully!";
        
    } while (false);

    // Close the connection
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Client</title>
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
        <h2>Update Client Information</h2>
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
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
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

            <?php
            // Display success message if any
            if (!empty($successMessage)) {
                echo "
                <div class='row mb-3'>
                  <div class='offset-sm-3 col-sm-6'>
                         <div class='alert alert-success alert-dismissible fade show' role='alert'>
                         <strong>$successMessage</strong>
                          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                </div>
                <script>
                    redirectAfterDelay('/projects/myshop/index.php', 2000); // Redirect after 2 seconds
                </script>
                ";
            }
            ?>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/projects/myshop/index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
