-- User Table
-- needs: club Table
CREATE TABLE IF NOT EXISTS users (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birthdate TIMESTAMP NOT NULL,
    club_id BIGINT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password BLOB NOT NULL,
    email VARCHAR(320) NOT NULL,
    is_active BOOLEAN NOT NULL,
    is_verified BOOLEAN NOT NULL,
    FOREIGN KEY (club_id) REFERENCES club(id)
);