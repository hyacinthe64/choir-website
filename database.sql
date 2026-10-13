-- ============================================
-- NARADA CHOIR AT EMLR — DATABASE SCHEMA
-- ============================================
-- Run: mysql -u root -p < database.sql
-- Or import via phpMyAdmin > Import tab

CREATE DATABASE IF NOT EXISTS choir_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE choir_db;

-- ADMINS
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Default login: username=admin  password=admin123
INSERT INTO admins (username, password, full_name) VALUES
('admin','$2y$10$Q0ILD0wjOVZbag8CxIQHGeXqSrhezgEMNTJcaIg.cO0jZ9prW.Ga.','NARADA CHOIR Administrator');

-- MEMBERS
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female') NOT NULL,
    category ENUM('Man','Woman','Boy','Girl') NOT NULL,
    phone VARCHAR(30),
    email VARCHAR(100) DEFAULT NULL,
    location VARCHAR(150) DEFAULT NULL,
    voice_part VARCHAR(30) DEFAULT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    joined_date DATE DEFAULT NULL,
    monthly_due DECIMAL(10,2) DEFAULT 1000.00,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ATTENDANCE
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present','Absent','Late') DEFAULT 'Present',
    remarks VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (member_id, attendance_date)
);

-- DUES
CREATE TABLE dues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    due_month INT NOT NULL,
    due_year INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('Paid','Unpaid') DEFAULT 'Unpaid',
    paid_date DATE DEFAULT NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    UNIQUE KEY unique_due (member_id, due_month, due_year)
);

-- SONGS
CREATE TABLE songs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    composer VARCHAR(100) DEFAULT NULL,
    category VARCHAR(60) DEFAULT NULL,
    file_path VARCHAR(255) NOT NULL,
    lyrics TEXT DEFAULT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- COMMITTEE
CREATE TABLE committee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    display_order INT DEFAULT 0
);

-- SAMPLE DATA
INSERT INTO members (full_name,gender,category,phone,email,location,voice_part,joined_date,monthly_due) VALUES
('Jean Mukiza','Male','Man','0788000001','jean.mukiza@email.com','Kicukiro, Kigali','Bass','2023-01-15',1000),
('Alice Uwase','Female','Woman','0788000002','alice.uwase@email.com','Gasabo, Kigali','Soprano','2023-02-10',1000),
('Eric Niyonsenga','Male','Man','0788000003','eric.niyonsenga@email.com','Nyarugenge, Kigali','Tenor','2023-03-05',1000),
('Grace Ingabire','Female','Woman','0788000004','grace.ingabire@email.com','Kicukiro, Kigali','Alto','2023-01-20',1000),
('David Mugisha','Male','Boy','0788000005','david.mugisha@email.com','Gasabo, Kigali','Tenor','2024-04-12',500),
('Sandrine Iradukunda','Female','Girl','0788000006','sandrine.iradukunda@email.com','Nyarugenge, Kigali','Soprano','2024-05-18',500);

INSERT INTO committee (full_name,role,bio,display_order) VALUES
('Pastor Samuel K.','Choir Director','Leads rehearsals and oversees all choir activities.',1),
('Alice Uwase','Secretary','Keeps records of meetings and member communication.',2),
('Jean Mukiza','Treasurer','Manages choir dues and finances.',3);

-- IF YOU ALREADY HAVE THE DB and need to add email/location columns:
-- ALTER TABLE members ADD COLUMN email VARCHAR(100) DEFAULT NULL AFTER phone;
-- ALTER TABLE members ADD COLUMN location VARCHAR(150) DEFAULT NULL AFTER email;

-- TO CHANGE ADMIN PASSWORD:
-- php -r "echo password_hash('newpassword', PASSWORD_DEFAULT);"
-- UPDATE admins SET password='PASTE_HASH' WHERE username='admin';
