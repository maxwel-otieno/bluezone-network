<?php

$dbname = 'bluezone_network';
$host = 'localhost';
$dbuser = 'root';
$pass = '';


try {
    $dsn = 'mysql: host='.$host.';dbname='.$dbname;
    $pdo = new PDO($dsn, $dbuser, $pass);
    // $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);

    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
}catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage()."<br>";
}

//insert package orders into the database
$pkg_order = $pdo->prepare("INSERT INTO pkg_order (`username`, `phone`, `pkg_id`, `date_ordered`) VALUES(?, ?, ?, NOW())");

//insert comments into the DB
$insert_comment = $pdo->prepare("INSERT INTO messages (`full_name`, `email`, `phone`, `message`, `date_submitted`) VALUES(?, ?, ?, ?, NOW())");

//Select the bundles data
$query_bundle = $pdo->prepare("SELECT id FROM packages WHERE pkg_name=?");

// if($_SERVER['REQUEST_METHOD'] == 'POST'){
//     if(isset($_POST['sbt-package'])){
//         $username = $_POST['fullname'];
//         $mobile = $_POST['phone'];
//         $bundle = $_POST['package'];

//         echo $bundle;

//         $Err = "<span class='alert d-flex justify-content-center mb-5 spanAlert'>Thank you $username, We'll reach out to you soon.</span>";
//         // echo "Hallo";
//     }
// }
?>