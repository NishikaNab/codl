<?php
session_start();
function getInitials($name)
{
    $words = preg_split('/\s+/', trim($name));
    return strtoupper(substr($words[0][0] . ($words[1][0] ?? ''), 0, 2));
}

?>
<div class="header">
    <h2>Dashboard</h2>
    <div class="user-info">
        <div class="user-avatar"><?= getInitials($_SESSION['name']) ?></div>
        <div>
            <div><?= $_SESSION['name'] ?></div>
            <div style="font-size: 0.8rem; color: #6c757d;"><?= $_SESSION['role'] ?></div>
        </div>
    </div>
</div>