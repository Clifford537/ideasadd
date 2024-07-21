-- Create users table with a unique username
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    country VARCHAR(50)
);

-- Create ideas table with username as a foreign key
CREATE TABLE ideas (
    idea_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    username VARCHAR(50),
    country VARCHAR(50),
    problem_heading VARCHAR(100),
    description TEXT,
    possible_solution TEXT,
    suggested_tools TEXT,
    impact_on_economy TEXT,
    revenue_generation TEXT,
    stakeholders TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (username) REFERENCES users(username)
);
