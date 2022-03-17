-- needs ranking and user
CREATE TABLE IF NOT EXISTS user_ranking (
    user_id BIGINT NOT NULL FOREIGN KEY REFERENCES club(id),
    ranking_id BIGINT NOT NULL FOREIGN KEY REFERENCES ranking(id),
    PRIMARY KEY(user_id, ranking_id),
    confirmed BOOL NOT NULL,
    hidden BOOL NOT NULL
);
