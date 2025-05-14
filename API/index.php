<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "mysql";
$username = "root";
$password = getenv('MYSQL_ROOT_PASSWORD');
$dbname = getenv('MYSQL_DATABASE');

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM information_schema.tables WHERE table_schema = '$dbname'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

    }
}


if ($_GET["resource"] == "users") {
    $users = [
        [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ],
        [
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane@example.com'
        ],
        [
            'id' => 3,
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com'
        ]
    ];
} else {
    $users = array();
}

echo json_encode($users);
exit;


$conn->close();
?>