-- Test data for Campus Booking System
-- This file contains sample data for testing the User Management module

USE campus_booking_system;

-- Insert sample users with profiles
INSERT INTO User (role_id, user_name, email, password, status) VALUES
(1, 'admin', 'admin@campus.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
(2, 'staff1', 'staff1@campus.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
(3, 'student1', 'student1@campus.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
(3, 'student2', 'student2@campus.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- Insert corresponding user profiles
INSERT INTO User_profiles (user_id, full_name, student_code, phone, department, address) VALUES
(1, 'System Administrator', 'ADMIN001', '123-456-7890', 'IT Department', '123 Admin Street, Campus City'),
(2, 'John Smith', 'STAFF001', '234-567-8901', 'Library', '456 Staff Avenue, Campus City'),
(3, 'Alice Johnson', 'STU2021001', '345-678-9012', 'Computer Science', '789 Student Road, Campus City'),
(4, 'Bob Wilson', 'STU2021002', '456-789-0123', 'Business Administration', '012 Student Lane, Campus City');

-- Note: Passwords are hashed version of "password"
-- You can use these credentials to test:
-- Username: admin, Email: admin@campus.edu, Password: password
-- Username: staff1, Email: staff1@campus.edu, Password: password
-- Username: student1, Email: student1@campus.edu, Password: password
-- Username: student2, Email: student2@campus.edu, Password: password
