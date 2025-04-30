<?php
// Database connection parameters
$servername = "mysql";
$username = "root";
$password = getenv('MYSQL_ROOT_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');


echo var_dump($servername, $username, $password, $dbname);

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to MySQL successfully!";

// read schema
$sql = "SELECT * FROM information_schema.tables WHERE table_schema = '$dbname'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<br>Table: " . print_r($row);
    }
} else {
    echo "<br>No tables found in the database.";
}

// Close the connection
$conn->close();
echo "<br>Connection closed.";
?>