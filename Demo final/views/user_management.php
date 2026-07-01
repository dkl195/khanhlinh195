<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Campus Booking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .form-full {
            grid-column: 1 / -1;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management System</h1>
        <div id="alert"></div>

        <!-- Add User Form -->
        <div class="card">
            <h2>Add New User</h2>
            <form id="addUserForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="user_name">Username:</label>
                        <input type="text" id="user_name" name="user_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role_id">Role:</label>
                        <select id="role_id" name="role_id" required>
                            <option value="">Select Role</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="student_code">Student Code:</label>
                        <input type="text" id="student_code" name="student_code">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="department">Department:</label>
                        <input type="text" id="department" name="department">
                    </div>
                    <div class="form-group form-full">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit">Add User</button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="card">
            <h2>Users List</h2>
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Student Code</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit User</h2>
            <form id="editUserForm">
                <input type="hidden" id="edit_user_id">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_user_name">Username:</label>
                        <input type="text" id="edit_user_name" name="user_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email:</label>
                        <input type="email" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_password">Password (leave empty to keep current):</label>
                        <input type="password" id="edit_password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="edit_role_id">Role:</label>
                        <select id="edit_role_id" name="role_id" required>
                            <option value="">Select Role</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status:</label>
                        <select id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_full_name">Full Name:</label>
                        <input type="text" id="edit_full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_student_code">Student Code:</label>
                        <input type="text" id="edit_student_code" name="student_code">
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Phone:</label>
                        <input type="text" id="edit_phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="edit_department">Department:</label>
                        <input type="text" id="edit_department" name="department">
                    </div>
                    <div class="form-group form-full">
                        <label for="edit_address">Address:</label>
                        <textarea id="edit_address" name="address" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit">Update User</button>
            </form>
        </div>
    </div>

    <script>
        let currentEditingUserId = null;

        // Load roles
        async function loadRoles() {
            try {
                const response = await fetch('../controllers/UserController.php?action=getRoles');
                const roles = await response.json();
                
                const roleSelect = document.getElementById('role_id');
                const editRoleSelect = document.getElementById('edit_role_id');
                
                roleSelect.innerHTML = '<option value="">Select Role</option>';
                editRoleSelect.innerHTML = '<option value="">Select Role</option>';
                
                roles.forEach(role => {
                    roleSelect.innerHTML += `<option value="${role.roleID}">${role.role_name}</option>`;
                    editRoleSelect.innerHTML += `<option value="${role.roleID}">${role.role_name}</option>`;
                });
            } catch (error) {
                console.error('Error loading roles:', error);
            }
        }

        // Load users
        async function loadUsers() {
            try {
                const response = await fetch('../controllers/UserController.php?action=readAll');
                const users = await response.json();
                
                const tbody = document.getElementById('usersTableBody');
                tbody.innerHTML = '';
                
                users.forEach(user => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${user.user_id}</td>
                            <td>${user.user_name}</td>
                            <td>${user.email}</td>
                            <td>${user.full_name || 'N/A'}</td>
                            <td>${user.student_code || 'N/A'}</td>
                            <td>${user.role_name || 'N/A'}</td>
                            <td><span class="status-${user.status}">${user.status}</span></td>
                            <td class="actions">
                                <button onclick="editUser(${user.user_id})" class="btn-warning">Edit</button>
                                <button onclick="deleteUser(${user.user_id})" class="btn-danger">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }

        // Add user
        document.getElementById('addUserForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            data.status = 'active';
            
            try {
                const response = await fetch('../controllers/UserController.php?action=create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('User created successfully!', 'success');
                    e.target.reset();
                    loadUsers();
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Error creating user', 'danger');
            }
        });

        // Edit user
        async function editUser(userId) {
            try {
                const response = await fetch(`../controllers/UserController.php?action=readOne&id=${userId}`);
                const user = await response.json();
                
                if (user.user_id) {
                    currentEditingUserId = userId;
                    
                    // Populate form
                    document.getElementById('edit_user_id').value = user.user_id;
                    document.getElementById('edit_user_name').value = user.user_name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_role_id').value = user.role_id;
                    document.getElementById('edit_status').value = user.status;
                    document.getElementById('edit_full_name').value = user.full_name || '';
                    document.getElementById('edit_student_code').value = user.student_code || '';
                    document.getElementById('edit_phone').value = user.phone || '';
                    document.getElementById('edit_department').value = user.department || '';
                    document.getElementById('edit_address').value = user.address || '';
                    
                    // Show modal
                    document.getElementById('editModal').style.display = 'block';
                }
            } catch (error) {
                showAlert('Error loading user data', 'danger');
            }
        }

        // Update user
        document.getElementById('editUserForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            // Remove password if empty
            if (!data.password) {
                delete data.password;
            }
            
            try {
                const response = await fetch(`../controllers/UserController.php?action=update&id=${currentEditingUserId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('User updated successfully!', 'success');
                    document.getElementById('editModal').style.display = 'none';
                    loadUsers();
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Error updating user', 'danger');
            }
        });

        // Delete user
        async function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user?')) {
                return;
            }
            
            try {
                const response = await fetch(`../controllers/UserController.php?action=delete&id=${userId}`, {
                    method: 'POST'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('User deleted successfully!', 'success');
                    loadUsers();
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Error deleting user', 'danger');
            }
        }

        // Show alert
        function showAlert(message, type) {
            const alertDiv = document.getElementById('alert');
            alertDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            setTimeout(() => {
                alertDiv.innerHTML = '';
            }, 5000);
        }

        // Modal close
        document.querySelector('.close').addEventListener('click', () => {
            document.getElementById('editModal').style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = 'none';
            }
        });

        // Initialize
        loadRoles();
        loadUsers();
    </script>
</body>
</html>
