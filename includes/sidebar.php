<?php
// Get current file name
$currentPage = basename($_SERVER['PHP_SELF']);

function isActive($page)
{
    return $GLOBALS['currentPage'] === $page ? 'active' : '';
}
?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
        <h1>Asset Management</h1>
    </div>
    <ul class="nav-links">
        <li><a href="/codl/dashboard.php" class="<?= isActive('dashboard.php') ?>"><i>📊</i> Dashboard</a></li>
        <li><a href="#" class="<?= isActive('.php') ?>"><i>👥</i> Users</a></li>
        <li><a href="#" class="<?= isActive('.php') ?>"><i>💻</i> Assets</a></li>
        <li><a href="#" class="<?= isActive('.php') ?>"><i>🏛️</i> Faculty</a></li>
        <li><a href="#" class="<?= isActive('.php') ?>"><i>📋</i> Borrow Requests</a></li>
        <li><a href="#" class="<?= isActive('.php') ?>"><i>🔄</i> Transactions</a></li>
        <li><a href="#" class="<?= isActive('.php') ?>"><i>📦</i> Distribution</a></li>
        <li><a href="/codl/damage_report.php" class="<?= isActive('damage_report.php') ?>"><i>⚠️</i> Damage Reports</a></li>
        <li><a href="#"><i>🔧</i> Repairs</a></li>
        <li><a href="#"><i>📅</i> Maintenance</a></li>
    </ul>
</div>