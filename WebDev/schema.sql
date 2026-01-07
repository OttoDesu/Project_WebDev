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
    company VARCHAR(150) DEFAULT 'Company',
    salary VARCHAR(120),
    location VARCHAR(120),
    closing_date DATE NULL,
    requirements TEXT NULL,
    benefits TEXT NULL,
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_id INT NOT NULL,
    full_name VARCHAR(255) NULL,
    email VARCHAR(120) NULL,
    phone_number VARCHAR(50) NULL,
    resume VARCHAR(255) NULL,
    cover_letter TEXT NOT NULL,
    status ENUM('Pending', 'Accepted', 'Rejected') NOT NULL DEFAULT 'Pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password_hash, role)
VALUES ('Admin', 'admin@example.com', '$2y$10$OMS1vgvzlfzBx2O1ZBIg3emzasYc25aUDfbQ4qvidA6jZZa4IfBSO', 'admin')
ON DUPLICATE KEY UPDATE email = email;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample jobs
INSERT INTO jobs (id, title, description, company, salary, location, closing_date, requirements, benefits)
VALUES
    (1, 'Associate Officer (Card Ops-Application Processing)', 'The candidate should have a Diploma, preferably 1 - 2 years of experience. Fresh graduates are encouraged to apply.', 'ACME Bank', 'RM 3,000 - RM 3,500', 'Kuala Lumpur', '2026-02-28', 'Diploma in related field; 1-2 years experience preferred; Attention to detail.', 'Medical, annual leave, training allowance'),
    (2, 'Software Engineer', 'We are looking for a passionate software engineer with experience in Java and React. Minimum 2 years experience required.', 'TechNova', 'RM 4,500 - RM 6,000', 'Petaling Jaya', '2026-03-10', '2+ years Java; React proficiency; REST APIs; Git; Team collaboration.', 'Hybrid work; Health coverage; Training budget; Performance bonus'),
    (3, 'Marketing Executive', 'Join our marketing team to promote our brand. Strong communication and social media skills are a must.', 'BrightMedia', 'RM 3,000 - RM 4,000', 'Subang Jaya', '2026-03-15', '1-2 years marketing; Social media management; Copywriting; Analytics basics.', 'Medical; Phone allowance; Team outings'),
    (4, 'HR Specialist', 'Assist with recruitment, employee relations, and payroll management. 2 years of HR experience preferred.', 'PeopleFirst HR', 'RM 4,000 - RM 5,500', 'Kuala Lumpur', '2026-03-20', '2+ years HR; Payroll knowledge; Recruitment coordination; Employment law basics.', 'Medical; Flexible hours; Learning stipend'),
    (5, 'Business Analyst', 'Analyze business needs, define requirements, and deliver effective solutions. Must have strong problem-solving skills.', 'Insight Solutions', 'RM 5,000 - RM 6,500', 'Cyberjaya', '2026-03-25', '3+ years BA; Requirements gathering; SQL basics; Stakeholder management.', 'Medical; Remote-friendly; Certification support'),
    (6, 'Sales Manager', 'Lead the sales team to achieve company sales targets. 3 years of experience in a leadership role required.', 'NorthStar Sales', 'RM 6,000 - RM 8,000', 'Penang', '2026-04-01', '3+ years sales leadership; Pipeline management; Coaching; Negotiation skills.', 'Commission plan; Car allowance; Medical; Travel claims')
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), company = VALUES(company), salary = VALUES(salary), location = VALUES(location), closing_date = VALUES(closing_date), requirements = VALUES(requirements), benefits = VALUES(benefits);
