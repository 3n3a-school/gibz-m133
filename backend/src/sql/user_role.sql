CREATE TABLE IF NOT EXISTS user_role (
    user_id BIGINT NOT NULL FOREIGN KEY REFERENCES club(id),
    role_id BIGINT NOT NULL FOREIGN KEY REFERENCES role(id),
    PRIMARY KEY(user_id, role_id)
);
