-- needs ranking and user
CREATE TABLE IF NOT EXISTS user_ranking (
    user_id BIGINT,
    ranking_id BIGINT,
    PRIMARY KEY(user_id, ranking_id),
    confirmed BOOLEAN NOT NULL,
    hidden BOOLEAN NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (ranking_id) REFERENCES ranking(id)
);
