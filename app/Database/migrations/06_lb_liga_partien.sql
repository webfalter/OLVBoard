-- Partien
CREATE TABLE lb_liga_partien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    spieltag_id INT NOT NULL,
    heim_id INT NOT NULL,
    gast_id INT NOT NULL,
    zeit DATETIME DEFAULT NULL,
    h_tore INT DEFAULT NULL,
    g_tore INT DEFAULT NULL,
    absage VARCHAR(10) DEFAULT NULL,
    notiz TEXT DEFAULT NULL,
    report_url VARCHAR(255) DEFAULT NULL,
    spiel_nr VARCHAR(10) DEFAULT NULL,
    FOREIGN KEY (spieltag_id) REFERENCES lb_liga_spieltage(id) ON DELETE CASCADE,
    FOREIGN KEY (heim_id) REFERENCES lb_teams_global(id) ON DELETE CASCADE,
    FOREIGN KEY (gast_id) REFERENCES lb_teams_global(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_partie (spieltag_id, heim_id, gast_id, spiel_nr)
);
