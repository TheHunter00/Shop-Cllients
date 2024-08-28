<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <h2>List of Clients</h2>
        <br>
        <a class="btn btn-primary" href="/projects/myshop/create.php" role="button">Add New Client</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- php connect data base  -->
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "myshop";
                //create connection  
                 $connection = new mysqli($servername, $username, $password, $database);

                //  check connection 
                if ($connection->connect_error){
                    die("Connection Failed: ".$connection->connect_error);
                }

                // read all row from database table 
                $sql = "SELECT * FROM clients";
                $result = $connection->query($sql);

                if(!$result){
                    die("Invalid query: ".$connection->error);
                }

                // read data of each row 
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <th>{$row['id']}</th>
                        <th>{$row['name']}</th>
                        <th>{$row['email']}</th>
                        <th>{$row['phone']}</th>
                        <th>{$row['address']}</th>
                        <th>{$row['created_at']}</th>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/projects/myshop/edit.php?id={$row['id']}'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/projects/myshop/delete.php?id={$row['id']}'>Delete</a>
                        </td>
                    </tr>";
                }
                

                 ?>

              
            </tbody>
        </table>
    </div>
</body>

</html>