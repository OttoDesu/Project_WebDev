CREATE DATABASE IF NOT EXISTS job_board DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE job_board;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    salary VARCHAR(120),
    location VARCHAR(120),
    closing_date DATE NULL,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    cover_letter TEXT NOT NULL,
    status ENUM('Pending', 'Accepted', 'Rejected') NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password_hash, role)
VALUES ('Admin', 'admin@example.com', '$2y$10$OMS1vgvzlfzBx2O1ZBIg3emzasYc25aUDfbQ4qvidA6jZZa4IfBSO', 'admin')
ON DUPLICATE KEY UPDATE email = email;

-- Sample jobs
INSERT INTO jobs (id, title, description, salary, location, closing_date)
VALUES
    (1, 'Associate Officer (Card Ops-Application Processing)', 'The candidate should have a Diploma, preferably 1 - 2 years of experience. Fresh graduates are encouraged to apply.', 'RM 3,000 - RM 3,500', 'Kuala Lumpur', '2026-02-28'),
    (2, 'Software Engineer', 'We are looking for a passionate software engineer with experience in Java and React. Minimum 2 years experience required.', 'RM 4,500 - RM 6,000', 'Petaling Jaya', '2026-03-10'),
    (3, 'Marketing Executive', 'Join our marketing team to promote our brand. Strong communication and social media skills are a must.', 'RM 3,000 - RM 4,000', 'Subang Jaya', '2026-03-15'),
    (4, 'HR Specialist', 'Assist with recruitment, employee relations, and payroll management. 2 years of HR experience preferred.', 'RM 4,000 - RM 5,500', 'Kuala Lumpur', '2026-03-20'),
    (5, 'Business Analyst', 'Analyze business needs, define requirements, and deliver effective solutions. Must have strong problem-solving skills.', 'RM 5,000 - RM 6,500', 'Cyberjaya', '2026-03-25'),
    (6, 'Sales Manager', 'Lead the sales team to achieve company sales targets. 3 years of experience in a leadership role required.', 'RM 6,000 - RM 8,000', 'Penang', '2026-04-01')
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), salary = VALUES(salary), location = VALUES(location), closing_date = VALUES(closing_date);
