-- Create the database
CREATE DATABASE emergency_vehicle_dispatch;

-- Use the created database
USE emergency_vehicle_dispatch;

-- Create the vehicles table
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_name VARCHAR(255) NOT NULL,
    vehicle_type VARCHAR(50) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    plate_number VARCHAR(20) NOT NULL,
    hospital VARCHAR(255),
    contact_number VARCHAR(20)
);

-- Insert some sample data
INSERT INTO vehicles (vehicle_name, vehicle_type, latitude, longitude, plate_number, hospital, contact_number)
VALUES
    ('Fire Truck 1', 'Fire', 40.712776, -74.005974, 'ABC123', 'City Hospital', '123-456-7890'),
    ('Fire Truck 2', 'Fire', 34.052235, -118.243683, 'DEF456', 'County Hospital', '987-654-3210'),
    ('Police Car 1', 'Police', 41.878113, -87.629799, 'GHI789', NULL, NULL),
    ('Police Car 2', 'Police', 37.774929, -122.419418, 'JKL012', NULL, NULL),
    ('Ambulance 1', 'Ambulance', 51.507350, -0.127758, 'MNO345', 'Central Hospital', '555-123-4567'),
    ('Ambulance 2', 'Ambulance', 48.856613, 2.352222, 'PQR678', 'General Hospital', '888-999-0000');

-- Create a user table for future authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

