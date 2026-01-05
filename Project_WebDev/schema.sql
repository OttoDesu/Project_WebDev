CREATE DATABASE IF NOT EXISTS we_run CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE we_run;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','participant') DEFAULT 'participant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    category VARCHAR(120) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    venue VARCHAR(200) NOT NULL,
    distance_km DECIMAL(5,2) NOT NULL,
    fee DECIMAL(8,2) NOT NULL,
    status VARCHAR(32) DEFAULT 'available',
    capacity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    notes VARCHAR(255),
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password_hash, role)
VALUES ('Admin', 'admin@werun.test', '$2y$10$TLgTJE6WfNpSS0w3fmkpm.ZVzcOUotoxhrmSZtkixr861IyzpSZcS', 'admin')
ON DUPLICATE KEY UPDATE email=email;

INSERT INTO events (title, category, description, event_date, venue, distance_km, fee, status, capacity)
VALUES
('City Night Run', '10K', 'Night city lights and music stations.', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'Central Park', 10.00, 60.00, 'available', 200),
('Trail Explorer', 'Trail', 'Technical forest trails with aid stations.', DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'Pine Ridge', 21.00, 120.00, 'available', 150),
('Charity Fun Run', '5K', 'Family friendly charity run.', DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'Lakeside', 5.00, 30.00, 'available', 300)
ON DUPLICATE KEY UPDATE title=VALUES(title);
