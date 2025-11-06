CREATE TABLE IF NOT EXISTS lb_liga_team_values (
    liga_id INT NOT NULL,
    team_id INT NOT NULL,
    key_name VARCHAR(50) NOT NULL,
    key_value VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (liga_id, team_id, key_name),
    FOREIGN KEY (liga_id) REFERENCES lb_liga(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES lb_teams_global(id) ON DELETE CASCADE
);
