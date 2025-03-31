-- File Tracking Database Schema
CREATE DATABASE IF NOT EXISTS file_tracking;
USE file_tracking;

-- Incoming Documents Table
CREATE TABLE IF NOT EXISTS incoming (
  id INT AUTO_INCREMENT PRIMARY KEY,
  status VARCHAR(50) NOT NULL,
  control_no VARCHAR(50) UNIQUE NOT NULL,
  date_received DATE NOT NULL,
  office_requestor VARCHAR(100) NOT NULL,
  transaction_type ENUM('P.O.','CONTRACT','PRS','PAR','ICS','CLEARANCE','OTHER') NOT NULL,
  action_taken TEXT NOT NULL,
  date_forwarded DATE,
  received_by VARCHAR(100) NOT NULL,
  remarks TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Outgoing Documents Table
CREATE TABLE IF NOT EXISTS outgoing (
  id INT AUTO_INCREMENT PRIMARY KEY,
  control_no VARCHAR(50) UNIQUE NOT NULL,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  document VARCHAR(200) NOT NULL,
  client_name VARCHAR(100) NOT NULL,
  agency_office VARCHAR(200) NOT NULL,
  contact_no VARCHAR(20),
  action_taken TEXT NOT NULL,
  acted_by VARCHAR(100) NOT NULL,
  date_acted DATE NOT NULL,
  remarks TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Data for Testing
INSERT INTO incoming (status, control_no, date_received, office_requestor, transaction_type, action_taken, received_by) 
VALUES ('Pending', 'INC-2023-001', '2023-10-15', 'Finance Dept', 'P.O.', 'For approval', 'John Doe');

INSERT INTO outgoing (control_no, `date`, `time`, document, client_name, agency_office, acted_by, date_acted, action_taken)
VALUES ('OUT-2023-001', '2023-10-16', '14:30:00', 'Contract Agreement', 'ABC Corporation', '123 Business St', 'Jane Smith', '2023-10-16', 'Delivered');