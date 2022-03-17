-- User Table
-- needs: club Table
CREATE TABLE IF NOT EXISTS user (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY_KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birthdate TIMESTAMP NOT NULL,
    club_id BIGINT FOREIGN KEY REFERENCES club(club_id),
    username VARCHAR(100) NOT NULL UNIQUE,
    password BLOB NOT NULL,
    email VARCHAR(320) NOT NULL,
    is_active BOOL NOT NULL,
    is_verified BOOL NOT NULL
);
