<?php
// Connect to database
include 'db_connection.php';

// Example queries

// Total assets
$total_assets_sql = "SELECT COUNT(*) as total FROM assets";
$total_assets_result = $conn->query($total_assets_sql);
$total_assets = 0;
if ($total_assets_result->num_rows > 0) {
    $row = $total_assets_result->fetch_assoc();
    $total_assets = $row['total'];
}

// Distributed assets
$distributed_assets_sql = "SELECT COUNT(*) as total FROM distributed_assets";
$distributed_assets_result = $conn->query($distributed_assets_sql);
$distributed_assets = 0;
if ($distributed_assets_result->num_rows > 0) {
    $row = $distributed_assets_result->fetch_assoc();
    $distributed_assets = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CODL Dashboard</title>
    <style>
        body { font-family: Arial; background-color: #f4f6f8; margin:0; padding:0;}
        header {background:#1f2937; color:white; padding:15px 30px;}
        .container {padding:30px;}
        .card {background:white; padding:20px; border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); margin-bottom:20px;}
        .card h3 {margin:0;}
        .card p {font-size:24px; margin-top:10px; font-weight:bold;}
    </style>
</head>
<body>
<header>
    <h1>CODL Asset Dashboard</h1>
</header>
<div class="container">
    <div class="card">
        <h3>Total Assets</h3>
        <p><?php echo $total_assets; ?></p>
    </div>
    <div class="card">
        <h3>Distributed Assets</h3>
        <p><?php echo $distributed_assets; ?></p>
    </div>
</div>
</body>
</html>
