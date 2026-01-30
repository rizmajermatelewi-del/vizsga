CREATE DATABASE vizsgaremek;
USE vizsgaremek;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50),password VARCHAR(255));
INSERT INTO `users`(username, password) VALUES ('admin','$2y$10$diDCWc9CzlBRJjWkUGBjWurij2X80vzKhXTo8PxqeLzy4GkJ9.Yum');
CREATE TABLE services(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),price INT,duration INT,description TEXT);
INSERT INTO services(name,price,duration) VALUES('Yumeiho',15000,60);
CREATE TABLE bookings(id INT AUTO_INCREMENT PRIMARY KEY,service_id INT,customer_name VARCHAR(100),email VARCHAR(100),phone VARCHAR(30),booking_date DATE,booking_time TIME,status VARCHAR(20) DEFAULT 'pending');
