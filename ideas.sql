-- Create users table with a unique username
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    country VARCHAR(50)
);

-- Create ideas table with user_id as a foreign key
CREATE TABLE ideas (
    idea_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
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
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Create comments table with user_id and idea_id as foreign keys
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    idea_id INT,
    user_id INT,
    comment_text TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idea_id) REFERENCES ideas(idea_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Create likes table with user_id and idea_id as foreign keys
CREATE TABLE likes (
    like_id INT AUTO_INCREMENT PRIMARY KEY,
    idea_id INT,
    user_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idea_id) REFERENCES ideas(idea_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
