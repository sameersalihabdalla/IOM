<?php
include('config.php');
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    echo "unauthorized";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doc              = $_POST['doc'];
    $name             = $_POST['name'];
    $date             = $_POST['date'];
    $type             = $_POST['type'];
    $broker_id        = $_POST['broker'];
    $premium          = $_POST['premium'];
    $passengers       = $_POST['passengers'];
    $StampCost        = $_POST['StampCost'];
    $SuperVisionCost  = $_POST['SuperVisionCost'];
    $issue            = $_POST['issue'];
    $SupportTax       = $_POST['SupportTax'];
    $commission_office= $_POST['commission_office'];
    $commission_agent = $_POST['commission_agent'];
    $TotalCost        = $_POST['TotalCost'];
    $note             = "insurance";

    try {
        $pdo = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO document 
            (name, document, date, type, premium, passengers, commission_office, commission_agent, issue, StampCost, SupportTax, SuperVisionCost, TotalCost, broker_id, note)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $success = $stmt->execute([
            $name, $doc, $date, $type, $premium, $passengers, $commission_office, $commission_agent,
            $issue, $StampCost, $SupportTax, $SuperVisionCost, $TotalCost, $broker_id, $note
        ]);

        echo $success ? "success" : "error";

    } catch (PDOException $e) {
        echo "db_error: " . $e->getMessage();
    }
}
?>
