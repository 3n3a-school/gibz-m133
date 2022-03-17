-- needs category and event
CREATE TABLE IF NOT EXISTS event_category_meta (
    event_id BIGINT NOT NULL FOREIGN KEY REFERENCES event(id),
    category_id BIGINT NOT NULL FOREIGN KEY REFERENCES category(id),
    PRIMARY KEY(event_id, category_id),
    key VARCHAR(50) NOT NULL,
    value VARCHAR(500) NOT NULL
);
