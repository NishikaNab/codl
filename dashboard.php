<?php
include('app/config/database.php');
include('app/models/User.php');
include('includes/header.php');

// $users = User::all();
?>

<!-- Dashboard Cards -->
<div class="dashboard-cards">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Total Assets</div>
            <div class="card-icon" style="background-color: var(--primary-color);">💻</div>
        </div>
        <div class="card-value">245</div>
        <div class="card-footer">12 new this month</div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Available Assets</div>
            <div class="card-icon" style="background-color: var(--success-color);">✅</div>
        </div>
        <div class="card-value">187</div>
        <div class="card-footer">76% availability</div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Borrowed Assets</div>
            <div class="card-icon" style="background-color: var(--warning-color);">📥</div>
        </div>
        <div class="card-value">42</div>
        <div class="card-footer">8 overdue returns</div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Under Maintenance</div>
            <div class="card-icon" style="background-color: var(--danger-color);">🔧</div>
        </div>
        <div class="card-value">16</div>
        <div class="card-footer">3 critical issues</div>
    </div>
</div>

<!-- Recent Assets Table -->
<div class="table-container">
    <div class="table-header">
        <div class="table-title">Recent Assets</div>
        <div class="table-actions">
            <button class="btn btn-primary" onclick="openAddAssetModal()">Add Asset</button>
            <button class="btn btn-secondary">Export</button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Asset ID</th>
                <th>Device Name</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>AST-001</td>
                <td>Laptop Dell XPS 13</td>
                <td>Dell</td>
                <td>Laptop</td>
                <td><span class="status status-available">Available</span></td>
                <td>
                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;">Edit</button>
                    <button class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                </td>
            </tr>
            <tr>
                <td>AST-002</td>
                <td>Projector Epson EB-U05</td>
                <td>Epson</td>
                <td>Projector</td>
                <td><span class="status status-borrowed">Borrowed</span></td>
                <td>
                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;">Edit</button>
                    <button class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                </td>
            </tr>
            <tr>
                <td>AST-003</td>
                <td>Tablet iPad Pro</td>
                <td>Apple</td>
                <td>Tablet</td>
                <td><span class="status status-maintenance">Maintenance</span></td>
                <td>
                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;">Edit</button>
                    <button class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                </td>
            </tr>
            <tr>
                <td>AST-004</td>
                <td>Monitor LG 27UL850</td>
                <td>LG</td>
                <td>Monitor</td>
                <td><span class="status status-available">Available</span></td>
                <td>
                    <button class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;">Edit</button>
                    <button class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Add Asset Form -->
<div class="form-container">
    <h3 style="margin-bottom: 20px; color: var(--secondary-color);">Add New Asset</h3>
    <form id="assetForm">
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="serialNumber">Serial Number</label>
                    <input type="text" id="serialNumber" name="serialNumber" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="deviceName">Device Name</label>
                    <input type="text" id="deviceName" name="deviceName" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="brand">Brand</label>
                    <input type="text" id="brand" name="brand" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="model">Model</label>
                    <input type="text" id="model" name="model" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="deviceType">Device Type</label>
                    <select id="deviceType" name="deviceType" required>
                        <option value="">Select Type</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Projector">Projector</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Printer">Printer</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="Available">Available</option>
                        <option value="Borrowed">Borrowed</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Retired">Retired</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="purchaseDate">Purchase Date</label>
                    <input type="date" id="purchaseDate" name="purchaseDate" required>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="purchasePrice">Purchase Price</label>
                    <input type="number" id="purchasePrice" name="purchasePrice" step="0.01" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="warrantyInfo">Warranty Information</label>
            <textarea id="warrantyInfo" name="warrantyInfo" rows="3"></textarea>
        </div>
        <div class="form-actions">
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn btn-primary">Add Asset</button>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>