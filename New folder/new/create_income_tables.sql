-- Create table for income categories
CREATE TABLE income_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL
);

-- Create table for income subcategories with foreign key reference
CREATE TABLE income_subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    subcategory_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (category_id) REFERENCES income_categories(id) ON DELETE CASCADE
);

-- Insert sample data into income categories
INSERT INTO income_categories (category_name) VALUES 
('Salary'),
('Investments'),
('Rental'),
('Business');

-- Insert sample data into income subcategories
INSERT INTO income_subcategories (category_id, subcategory_name) VALUES 
(1, 'Monthly Salary'),
(1, 'Bonus'),
(2, 'Stocks'),
(2, 'Fixed Deposits'),
(3, 'House Rent'),
(3, 'Commercial Rent'),
(4, 'Online Sales'),
(4, 'Consulting'); 