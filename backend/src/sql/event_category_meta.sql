-- needs category and event
CREATE TABLE IF NOT EXISTS event_category_meta (
    event_id BIGINT,
    category_id BIGINT, 
    PRIMARY KEY(event_id, category_id),
    name VARCHAR(50) NOT NULL,
    description VARCHAR(500) NOT NULL,
    FOREIGN KEY (event_id) REFERENCES event(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
);
