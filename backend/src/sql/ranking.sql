CREATE TABLE IF NOT EXISTS ranking (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    participant_name VARCHAR(100) NOT NULL,
    event_id BIGINT,
    category_id BIGINT,
    position int NOT NULL,
    time BIGINT NOT NULL,
    birthyear int NOT NULL,
    city VARCHAR(100) NOT NULL,
    FOREIGN KEY (event_id) REFERENCES event(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
);