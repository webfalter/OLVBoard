CREATE TABLE IF NOT EXISTS lb_liga_spieltage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga_id INT NOT NULL,
    nummer INT NOT NULL,
    start DATETIME DEFAULT NULL,
    ende DATETIME DEFAULT NULL,
    modus INT DEFAULT 0,
    FOREIGN KEY (liga_id) REFERENCES lb_liga(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_liga_spieltag (liga_id, nummer)
);
