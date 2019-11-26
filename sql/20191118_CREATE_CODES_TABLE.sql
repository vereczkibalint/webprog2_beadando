CREATE TABLE `codes` (
    `user_id` INT NOT NULL,
    `code_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `code_title` VARCHAR(50) NOT NULL,
    `code_description` TEXT NOT NULL,
    `code_path` VARCHAR(255) NOT NULL,
    `code_uploaded_at` DATE NOT NULL,
    CONSTRAINT FK_codes_users FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);