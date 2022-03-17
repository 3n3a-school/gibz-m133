CREATE TABLE IF NOT EXISTS ranking (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY_KEY,
    participant_name VARCHAR(100) NOT NULL,
    event_id BIGINT NOT NULL FOREIGN KEY REFERENCES event(id),
    category_id BIGINT NOT NULL FOREIGN KEY REFERENCES category(id),
    rank int NOT NULL,
    time BIGINT NOT NULL,
    birthyear int NOT NULL,
    city VARCHAR(100) NOT NULL
);