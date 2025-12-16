<?php
include("../database/conn.php"); // Make sure the path is correct

$query = 'SELECT user_id, email, password, name, phone, department, role, status FROM users ORDER BY user_id';
$result = pg_query($conn, $query);

if (!$result) {
    die("❌ Query failed: " . pg_last_error($conn));
}

$users = [];
while ($row = pg_fetch_assoc($result)) {
    $users[] = $row;
}

// Close connection after all queries
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Asset Management System</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --border-radius: 4px;
            --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: var(--secondary-color);
            color: white;
            padding: 20px 0;
            transition: all 0.3s;
        }

        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links li {
            padding: 10px 20px;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
        }

        .nav-links i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .header h2 {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-title {
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 500;
            color: var(--secondary-color);
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .role {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .role-admin {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .role-faculty {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }

        .role-staff {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .role-student {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        /* Form Styles */
        .form-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-col {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: var(--border-radius);
            width: 600px;
            max-width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Search and Filter */
        .search-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-box {
            flex: 1;
            position: relative;
        }

        .search-box input {
            padding-left: 40px;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .filter-select {
            min-width: 150px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .search-filter {
                flex-direction: column;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .table-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h1>Asset Management</h1>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.html"><i>📊</i> Dashboard</a></li>
                <li><a href="#" class="active"><i>👥</i> Users</a></li>
                <li><a href="assets.html"><i>💻</i> Assets</a></li>
                <li><a href="faculty.html"><i>🏛️</i> Faculty</a></li>
                <li><a href="borrow-requests.html"><i>📋</i> Borrow Requests</a></li>
                <li><a href="transactions.html"><i>🔄</i> Transactions</a></li>
                <li><a href="distribution.html"><i>📦</i> Distribution</a></li>
                <li><a href="damage-reports.html"><i>⚠️</i> Damage Reports</a></li>
                <li><a href="repairs.html"><i>🔧</i> Repairs</a></li>
                <li><a href="maintenance.html"><i>📅</i> Maintenance</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>User Management</h2>
                <div class="user-info">
                    <div class="user-avatar">JD</div>
                    <div>
                        <div>John Doe</div>
                        <div style="font-size: 0.8rem; color: #6c757d;">Administrator</div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="search-filter">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search users by name, email, or department...">
                </div>
                <select class="filter-select" id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="Admin">Admin</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Staff">Staff</option>
                    <option value="Student">Student</option>
                </select>
                <select class="filter-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <!-- Users Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">All Users</div>
                    <div class="table-actions">
                        <button class="btn btn-primary" onclick="openAddUserModal()">Add User</button>
                        <button class="btn btn-secondary">Export</button>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>USR-<?php echo str_pad($user['user_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['department']); ?></td>
                                <td><span class="role role-<?php echo strtolower($user['role']); ?>"><?php echo htmlspecialchars($user['role']); ?></span></td>
                                <td><span class="status status-<?php echo strtolower($user['status']); ?>"><?php echo htmlspecialchars($user['status']); ?></span></td>
                                <td>
                                    <button class="btn btn-secondary btn-sm" onclick="editUser(<?php echo $user['user_id']; ?>)">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['user_id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <!-- Add User Form -->
            <div class="form-container">
                <h3 style="margin-bottom: 20px; color: var(--secondary-color);">Add New User</h3>
                <form id="userForm">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <input type="text" id="department" name="department">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Faculty">Faculty</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Student">Student</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal" id="addUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add New User</div>
                <button class="modal-close" onclick="closeAddUserModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="modalUserForm">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="modalName">Full Name</label>
                                <input type="text" id="modalName" name="modalName" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="modalEmail">Email Address</label>
                                <input type="email" id="modalEmail" name="modalEmail" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="modalPassword">Password</label>
                                <input type="password" id="modalPassword" name="modalPassword" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="modalPhone">Phone Number</label>
                                <input type="tel" id="modalPhone" name="modalPhone">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="modalDepartment">Department</label>
                                <input type="text" id="modalDepartment" name="modalDepartment">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="modalRole">Role</label>
                                <select id="modalRole" name="modalRole" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Faculty">Faculty</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Student">Student</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAddUserModal()">Cancel</button>
                <button class="btn btn-primary" onclick="submitUserForm()">Save User</button>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal" id="editUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Edit User</div>
                <button class="modal-close" onclick="closeEditUserModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="editName">Full Name</label>
                                <input type="text" id="editName" name="editName" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="editEmail">Email Address</label>
                                <input type="email" id="editEmail" name="editEmail" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="editPassword">Password (leave blank to keep current)</label>
                                <input type="password" id="editPassword" name="editPassword">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="editPhone">Phone Number</label>
                                <input type="tel" id="editPhone" name="editPhone">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="editDepartment">Department</label>
                                <input type="text" id="editDepartment" name="editDepartment">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="editRole">Role</label>
                                <select id="editRole" name="editRole" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Faculty">Faculty</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Student">Student</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select id="editStatus" name="editStatus" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeEditUserModal()">Cancel</button>
                <button class="btn btn-primary" onclick="updateUser()">Update User</button>
            </div>
        </div>
    </div>

   <script>
const users = <?php echo json_encode($users); ?>;

// Initialize the users table
function initializeUsersTable() {
    const tableBody = document.getElementById('usersTableBody');
    tableBody.innerHTML = '';

    users.forEach(user => {
        const row = document.createElement('tr');

        const roleClass = `role-${user.role.toLowerCase()}`;
        const statusClass = user.status ? `status-${user.status.toLowerCase()}` : '';

        row.innerHTML = `
            <td>USR-${user.user_id.toString().padStart(3, '0')}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.phone}</td>
            <td>${user.department}</td>
            <td><span class="role ${roleClass}">${user.role}</span></td>
            <td>${user.status ? `<span class="status ${statusClass}">${user.status}</span>` : ''}</td>
            <td>
                <button class="btn btn-secondary btn-sm" onclick="editUser(${user.user_id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.user_id})">Delete</button>
            </td>
        `;

        tableBody.appendChild(row);
    });
}
</script>

</body>

</html>