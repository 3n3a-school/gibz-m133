-- needs ranking and user
CREATE TABLE IF NOT EXISTS user_ranking (
    user_id BIGINT,
    ranking_id BIGINT,
    PRIMARY KEY(user_id, ranking_id),
    confirmed BOOLEAN NULL DEFAULT 0,
    hidden BOOLEAN NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (ranking_id) REFERENCES ranking(id)
);
