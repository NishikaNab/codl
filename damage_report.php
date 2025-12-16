<?php
include('app/config/database.php');
include('app/models/User.php');
include('app/models/Asset.php');
include('app/models/Damage.php');
include('includes/header.php');

$users = User::all();
$admins = User::getStaffByRole('admin');
$staffs = User::getStaffByRole('staff');
$assests = Asset::all();
$damage_levels = Damage::getDamageLevel();
?>
 
<!-- Dashboard Cards -->
<?php foreach($admins as $admin) {
    echo 'admin: ' . $admin['name'];
} ?>

<?php foreach($staffs as $staff) {
    echo 'staff: ' . $staff['name'];
} ?>

<?php foreach($staffs as $staff) {
    echo 'staff: ' . $staff['name'];
} ?>
<div class="dashboard-cards">
    <div class="card" onclick="filterByStatus('')">
        <div class="card-header">
            <div class="card-title">Total Reports</div>
            <div class="card-icon" style="background-color: var(--primary-color);">⚠️</div>
        </div>
        <div class="card-value">34</div>
        <div class="card-footer">All damage reports</div>
    </div>
    <div class="card" onclick="filterByStatus('reported')">
        <div class="card-header">
            <div class="card-title">New Reports</div>
            <div class="card-icon" style="background-color: var(--warning-color);">📝</div>
        </div>
        <div class="card-value">8</div>
        <div class="card-footer">Awaiting review</div>
    </div>
    <div class="card" onclick="filterByStatus('repair-in-progress')">
        <div class="card-header">
            <div class="card-title">Under Repair</div>
            <div class="card-icon" style="background-color: var(--primary-color);">🔧</div>
        </div>
        <div class="card-value">12</div>
        <div class="card-footer">Currently being repaired</div>
    </div>
    <div class="card" onclick="filterBySeverity('high')">
        <div class="card-header">
            <div class="card-title">Critical Damage</div>
            <div class="card-icon" style="background-color: var(--danger-color);">🚨</div>
        </div>
        <div class="card-value">5</div>
        <div class="card-footer">High severity issues</div>
    </div>
</div>

<!-- Search and Filter -->
<div class="search-filter">
    <div class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" id="searchInput" placeholder="Search reports by asset, user, or description...">
    </div>
    <select class="filter-select" id="statusFilter">
        <option value="">All Status</option>
        <option value="reported">Reported</option>
        <option value="under-review">Under Review</option>
        <option value="repair-scheduled">Repair Scheduled</option>
        <option value="repair-in-progress">Repair in Progress</option>
        <option value="resolved">Resolved</option>
        <option value="unrepairable">Unrepairable</option>
    </select>
    <select class="filter-select" id="severityFilter">
        <option value="">All Severity</option>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
        <option value="critical">Critical</option>
    </select>
</div>

<!-- Damage Reports Table -->
<div class="table-container">
    <div class="table-header">
        <div class="table-title">All Damage Reports</div>
        <div class="table-actions">
            <button class="btn btn-primary" onclick="openNewReportModal()">New Report</button>
            <button class="btn btn-secondary">Export</button>
            <button class="btn btn-warning" onclick="viewDamageReport()">Generate Report</button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Asset</th>
                <th>Reported By</th>
                <th>Damage Description</th>
                <th>Severity</th>
                <th>Report Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="reportsTableBody">
            <!-- Reports will be populated by JavaScript -->
        </tbody>
    </table>
</div>

<!-- New Damage Report Form -->
<div class="form-container">
    <h3 style="margin-bottom: 20px; color: var(--secondary-color);">Create New Damage Report</h3>
    <form id="damageReportForm">
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="assetId">Select Asset</label>
                    <select id="assetId" name="assetId" required>
                        <option value="">Select Asset</option>
                        <?php foreach($assests as $asset): ?>
                            <option value="<?= $asset['asset_id'] ?>"><?= $asset['device_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="userId">Reported By</label>
                    <select id="userId" name="userId" required>
                        <option value="">Select User</option>
                        <?php foreach($users as $user): ?>
                            <option value="<?= $user['user_id'] ?>"><?= $user['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="severity">Damage Level</label>
                    <select id="severity" name="severity" required>
                        <option value="">Select Level</option>
                        <option value="low">Low - Minor cosmetic damage</option>
                        <option value="medium">Medium - Functional but impaired</option>
                        <option value="high">High - Major functional issues</option>
                        <option value="critical">Critical - Complete failure</option>
                    </select>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="reportDate">Report Date</label>
                    <input type="datetime-local" id="reportDate" name="reportDate" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="damageDescription">Damage Description</label>
            <textarea id="damageDescription" name="damageDescription" rows="4" placeholder="Please provide detailed description of the damage, including how it occurred and current condition..." required></textarea>
        </div>
        <div class="form-group">
            <label>Damage Evidence (Photos)</label>
            <div class="image-upload" onclick="document.getElementById('imageUpload').click()">
                <div class="upload-icon">📷</div>
                <div>Click to upload damage photos</div>
                <div style="font-size: 0.8rem; color: #6c757d; margin-top: 5px;">Supports JPG, PNG (Max 5MB each)</div>
            </div>
            <input type="file" id="imageUpload" multiple accept="image/*" style="display: none;">
            <div class="image-preview" id="imagePreview">
                <!-- Image previews will be added here -->
            </div>
        </div>
        <div class="form-actions">
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn btn-primary">Submit Report</button>
        </div>
    </form>
</div>
</div>
</div>

<!-- New Report Modal -->
<div class="modal" id="newReportModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Create New Damage Report</div>
            <button class="modal-close" onclick="closeNewReportModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="modalReportForm">
                <div class="form-group">
                    <label for="modalAssetId">Select Asset</label>
                    <select id="modalAssetId" name="modalAssetId" required>
                        <option value="">Select Asset</option>
                        <option value="1">Laptop Dell XPS 13 (Currently Borrowed)</option>
                        <option value="2">Projector Epson EB-U05 (Available)</option>
                        <option value="3">Tablet iPad Pro (Under Maintenance)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modalUserId">Reported By</label>
                    <select id="modalUserId" name="modalUserId" required>
                        <option value="">Select User</option>
                        <option value="1">John Doe (Engineering)</option>
                        <option value="2">Jane Smith (Medicine)</option>
                        <option value="3">Robert Johnson (Business)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modalSeverity">Damage Severity</label>
                    <select id="modalSeverity" name="modalSeverity" required>
                        <option value="">Select Severity</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="modalDamageDescription">Damage Description</label>
                    <textarea id="modalDamageDescription" name="modalDamageDescription" rows="4" required></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeNewReportModal()">Cancel</button>
            <button class="btn btn-primary" onclick="submitReportForm()">Submit Report</button>
        </div>
    </div>
</div>

<!-- Damage Report Details Modal -->
<div class="modal" id="reportDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Damage Report Details</div>
            <button class="modal-close" onclick="closeReportDetailsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="damage-details">
                <div>
                    <div class="detail-group">
                        <div class="detail-label">Report ID</div>
                        <div class="detail-value" id="detailReportId">DAM-001</div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Report Date</div>
                        <div class="detail-value" id="detailReportDate">2024-02-20 14:30</div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Severity</div>
                        <div class="detail-value">
                            <span class="severity severity-high" id="detailSeverity">High</span>
                        </div>
                    </div>
                    <div class="detail-group">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status status-under-review" id="detailStatus">Under Review</span>
                        </div>
                    </div>
                    <div class="user-info-card">
                        <div class="detail-label" style="color: var(--primary-color); margin-bottom: 10px;">Reporter Information</div>
                        <div class="detail-group">
                            <div class="detail-label">Name</div>
                            <div class="detail-value" id="detailUserName">John Doe</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Department</div>
                            <div class="detail-value" id="detailUserDepartment">Faculty of Engineering</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Email</div>
                            <div class="detail-value" id="detailUserEmail">john.doe@university.edu</div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="asset-info-card">
                        <div class="detail-label" style="color: var(--primary-color); margin-bottom: 10px;">Asset Information</div>
                        <div class="detail-group">
                            <div class="detail-label">Asset Name</div>
                            <div class="detail-value" id="detailAssetName">Laptop Dell XPS 13</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Serial Number</div>
                            <div class="detail-value" id="detailAssetSerial">SN-789456123</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Brand & Model</div>
                            <div class="detail-value" id="detailAssetModel">Dell XPS 13 9310</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Current Status</div>
                            <div class="detail-value">
                                <span class="status status-maintenance" id="detailAssetStatus">Under Maintenance</span>
                            </div>
                        </div>
                    </div>
                    <div class="damage-info-card">
                        <div class="detail-label" style="color: var(--warning-color); margin-bottom: 10px;">Damage Information</div>
                        <div class="detail-group">
                            <div class="detail-label">Damage Description</div>
                            <div class="detail-value" id="detailDamageDescription" style="background-color: #fff3cd; padding: 10px; border-radius: var(--border-radius);">Screen cracked after accidental drop. Device powers on but display is completely black. No visible damage to casing except for minor scratches.</div>
                        </div>
                        <div class="detail-group">
                            <div class="detail-label">Damage Photos</div>
                            <div class="image-preview">
                                <div class="preview-item">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23e9ecef'/%3E%3Ctext x='50' y='50' font-family='Arial' font-size='12' fill='%236c757d' text-anchor='middle' dominant-baseline='middle'%3EDamage Photo%3C/text%3E%3C/svg%3E" alt="Damage photo">
                                    <button class="preview-remove">&times;</button>
                                </div>
                                <div class="preview-item">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23e9ecef'/%3E%3Ctext x='50' y='50' font-family='Arial' font-size='12' fill='%236c757d' text-anchor='middle' dominant-baseline='middle'%3EDamage Photo%3C/text%3E%3C/svg%3E" alt="Damage photo">
                                    <button class="preview-remove">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="timeline">
                        <h4 style="margin-bottom: 15px; color: var(--secondary-color);">Report Timeline</h4>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">2024-02-20 14:30</div>
                                <div class="timeline-description">Damage reported by John Doe</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">2024-02-20 15:15</div>
                                <div class="timeline-description">Report assigned for review</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-date">2024-02-21 09:00</div>
                                <div class="timeline-description">Initial assessment completed</div>
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons" id="actionButtons">
                        <!-- Action buttons will be populated based on status -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeReportDetailsModal()">Close</button>
            <button class="btn btn-primary" onclick="printDamageReport()">Print Report</button>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>