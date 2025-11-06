CREATE TABLE IF NOT EXISTS lb_liga_teams (
    liga_id INT NOT NULL,
    team_id INT NOT NULL,
    PRIMARY KEY (liga_id, team_id),
    FOREIGN KEY (liga_id) REFERENCES lb_liga(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES lb_teams_global(id) ON DELETE CASCADE
);
