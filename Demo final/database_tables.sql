-- Database creation
CREATE DATABASE IF NOT EXISTS campus_booking_system;
USE campus_booking_system;

-- 1. Roles table
CREATE TABLE Roles (
    roleID INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- 2. Service types table
CREATE TABLE Service_types (
    typeID INT PRIMARY KEY AUTO_INCREMENT,
    type_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- 3. Booking statuses table
CREATE TABLE booking_statuses (
    statusID INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- 4. User table
CREATE TABLE User (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    user_name VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES Roles(roleID) ON DELETE CASCADE
);

-- 5. User_profiles table
CREATE TABLE User_profiles (
    profile_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    student_code VARCHAR(20) UNIQUE,
    phone VARCHAR(20),
    department VARCHAR(100),
    address TEXT,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

-- 6. Campus_services table
CREATE TABLE Campus_services (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    type_id INT NOT NULL,
    service_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    capacity INT NOT NULL DEFAULT 1,
    price_per_hour DECIMAL(10, 2) DEFAULT 0.00,
    description TEXT,
    status ENUM('available', 'unavailable', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_id) REFERENCES Service_types(typeID) ON DELETE CASCADE
);

-- 7. Bookings table
CREATE TABLE Bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    status_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    purpose VARCHAR(255) NOT NULL,
    note TEXT,
    approved_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Campus_services(service_id) ON DELETE CASCADE,
    FOREIGN KEY (status_id) REFERENCES booking_statuses(statusID) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES User(user_id) ON DELETE SET NULL,
    CHECK (end_time > start_time)
);

-- 8. Feedbacks table
CREATE TABLE Feedbacks (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL UNIQUE,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES Bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

-- 9. service_Images table
CREATE TABLE service_Images (
    image_id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    caption VARCHAR(255),
    FOREIGN KEY (service_id) REFERENCES Campus_services(service_id) ON DELETE CASCADE
);

-- Insert default data for lookup tables
INSERT INTO Roles (role_name, description) VALUES
('Admin', 'System administrator with full access'),
('Staff', 'Staff member with limited administrative access'),
('Student', 'Student user who can book services');

INSERT INTO Service_types (type_name, description) VALUES
('Meeting Room', 'Conference and meeting spaces'),
('Laboratory', 'Science and computer labs'),
('Sports Facility', 'Gym, field, and sports equipment'),
('Study Room', 'Quiet study spaces and libraries'),
('Event Space', 'Large venues for events and gatherings');

INSERT INTO booking_statuses (status_name, description) VALUES
('Pending', 'Booking awaiting approval'),
('Approved', 'Booking approved and confirmed'),
('Rejected', 'Booking rejected by administrator'),
('Completed', 'Booking completed successfully'),
('Cancelled', 'Booking cancelled by user');

-- Create indexes for better performance
CREATE INDEX idx_user_email ON User(email);
CREATE INDEX idx_user_username ON User(user_name);
CREATE INDEX idx_booking_date ON Bookings(booking_date);
CREATE INDEX idx_service_type ON Campus_services(type_id);
CREATE INDEX idx_service_status ON Campus_services(status);
CREATE INDEX idx_booking_user ON Bookings(user_id);
CREATE INDEX idx_booking_service ON Bookings(service_id);
