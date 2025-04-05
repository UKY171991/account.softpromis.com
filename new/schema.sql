-- Table: clients --

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: income --

CREATE TABLE income (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    subcategory VARCHAR(100),
    amount DECIMAL(10,2),
    received DECIMAL(10,2),
    balance DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: expenditures --

CREATE TABLE expenditures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    subcategory VARCHAR(100),
    amount DECIMAL(10,2),
    paid DECIMAL(10,2),
    balance DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: users --

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Sample Data Inserts --

-- Table: clients --

INSERT INTO clients (name, phone, email, address) VALUES
('John Doe', '9876543210', 'john@example.com', '123 Main Street'),
('Jane Smith', '9123456789', 'jane@example.com', '456 Elm Avenue');


-- Table: income --

INSERT INTO income (date, name, category, subcategory, amount, received, balance) VALUES
('2025-04-01', 'John Doe', 'Consulting', 'IT Services', 10000.00, 7000.00, 3000.00),
('2025-04-02', 'Jane Smith', 'Sales', 'Product A', 15000.00, 15000.00, 0.00);


-- Table: expenditures --

INSERT INTO expenditures (date, name, category, subcategory, amount, paid, balance) VALUES
('2025-04-01', 'Office Supplies', 'Operations', 'Stationery', 2000.00, 2000.00, 0.00),
('2025-04-03', 'Electricity Bill', 'Utilities', 'Monthly', 5000.00, 4000.00, 1000.00);


-- Table: users --

INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$examplehashforadminpassword', 'admin'),
('staff', '$2y$10$examplehashforstaffpassword', 'employee');
-- Note: Replace the above hashes with real bcrypt hashed passwords using PHP's password_hash()

