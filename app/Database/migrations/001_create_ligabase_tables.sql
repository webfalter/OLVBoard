-- Liga
CREATE TABLE lb_liga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    kurz VARCHAR(50) DEFAULT NULL,
    datum TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_liga_name (name)
);

-- Globale Teams
CREATE TABLE lb_teams_global (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    kurz VARCHAR(50) DEFAULT NULL,
    mittel VARCHAR(100) DEFAULT NULL,
    UNIQUE KEY uniq_team_name (name)
);

-- Liga-Teams Verknüpfung
CREATE TABLE lb_liga_teams (
    liga_id INT NOT NULL,
    team_id INT NOT NULL,
    PRIMARY KEY (liga_id, team_id),
    FOREIGN KEY (liga_id) REFERENCES lb_liga(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES lb_teams_global(id) ON DELETE CASCADE
);

-- Spieltage
CREATE TABLE lb_liga_spieltage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga_id INT NOT NULL,
    nummer INT NOT NULL,
    start DATETIME DEFAULT NULL,
    ende DATETIME DEFAULT NULL,
    modus INT DEFAULT 0,
    FOREIGN KEY (liga_id) REFERENCES lb_liga(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_liga_spieltag (liga_id, nummer)
);

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

-- Liga Options
CREATE TABLE lb_liga_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga_id INT NOT NULL,
    option_key VARCHAR(100) NOT NULL,
    option_value TEXT DEFAULT NULL,
    FOREIGN KEY (liga_id) REFERENCES lb_liga(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_liga_option (liga_id, option_key)
);

-- Liga Admin
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

