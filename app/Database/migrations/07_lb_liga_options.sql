CREATE TABLE IF NOT EXISTS lb_liga_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga_id INT NOT NULL,
    option_key VARCHAR(100) NOT NULL,
    option_value TEXT DEFAULT NULL,
    FOREIGN KEY (liga_id) REFERENCES lb_liga(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_liga_option (liga_id, option_key)
);
