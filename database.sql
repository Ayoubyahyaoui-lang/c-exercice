-- BETZONE216 MySQL schema
CREATE DATABASE IF NOT EXISTS betzone216 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE betzone216;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  balance DECIMAL(12,2) NOT NULL DEFAULT 1000.00,
  status ENUM('active','suspended') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  permissions JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE sports (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL UNIQUE,
  slug VARCHAR(60) NOT NULL UNIQUE,
  icon VARCHAR(30) NOT NULL DEFAULT 'trophy'
);

CREATE TABLE leagues (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sport_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  country VARCHAR(80) NOT NULL DEFAULT '',
  FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
);

CREATE TABLE matches (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  league_id INT UNSIGNED NOT NULL,
  home_team VARCHAR(100) NOT NULL,
  away_team VARCHAR(100) NOT NULL,
  start_at DATETIME NOT NULL,
  status ENUM('upcoming','live','finished','cancelled') NOT NULL DEFAULT 'upcoming',
  home_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
  away_score TINYINT UNSIGNED NOT NULL DEFAULT 0,
  minute SMALLINT UNSIGNED NULL,
  is_featured BOOLEAN NOT NULL DEFAULT FALSE,
  FOREIGN KEY (league_id) REFERENCES leagues(id) ON DELETE CASCADE
);

CREATE TABLE odds (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  match_id INT UNSIGNED NOT NULL,
  market ENUM('1X2','OVER_UNDER','BTTS') NOT NULL,
  selection VARCHAR(50) NOT NULL,
  label VARCHAR(50) NOT NULL,
  odd DECIMAL(8,3) NOT NULL,
  UNIQUE KEY (match_id, market, selection),
  FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE
);

CREATE TABLE bets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  stake DECIMAL(12,2) NOT NULL,
  total_odds DECIMAL(12,3) NOT NULL,
  potential_win DECIMAL(12,2) NOT NULL,
  status ENUM('pending','won','lost','cancelled') NOT NULL DEFAULT 'pending',
  placed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE bet_selections (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  bet_id INT UNSIGNED NOT NULL,
  match_id INT UNSIGNED NOT NULL,
  market VARCHAR(30) NOT NULL,
  selection VARCHAR(50) NOT NULL,
  label VARCHAR(50) NOT NULL,
  odd DECIMAL(8,3) NOT NULL,
  FOREIGN KEY (bet_id) REFERENCES bets(id) ON DELETE CASCADE,
  FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE
);

CREATE TABLE transactions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  type ENUM('deposit','withdrawal','bet','win','refund') NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  reference VARCHAR(100) NOT NULL,
  status ENUM('completed','pending','cancelled') NOT NULL DEFAULT 'completed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE banners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  subtitle VARCHAR(180) NOT NULL,
  accent VARCHAR(30) NOT NULL DEFAULT '#43f5a2',
  active BOOLEAN NOT NULL DEFAULT TRUE
);

INSERT INTO sports (name, slug, icon) VALUES
('Football','football','ball'),('Basketball','basketball','ball'),('Tennis','tennis','racquet'),('Ice Hockey','ice-hockey','puck'),('Esports','esports','game');

INSERT INTO leagues (sport_id,name,country) VALUES
(1,'UEFA Champions League','Europe'),(1,'Premier League','England'),(1,'La Liga','Spain'),
(2,'NBA','USA'),(3,'ATP Masters','International'),(4,'NHL','North America'),(5,'ESL Pro League','International');

INSERT INTO users (username,email,password_hash,role,balance) VALUES
('admin','admin@betzone216.local','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',10000.00),
('demo','demo@betzone216.local','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user',1000.00);
INSERT INTO admin (user_id,permissions) VALUES (1,'["all"]');

INSERT INTO matches (league_id,home_team,away_team,start_at,status,home_score,away_score,minute,is_featured) VALUES
(1,'Manchester City','Real Madrid',DATE_ADD(NOW(),INTERVAL 2 HOUR),'upcoming',0,0,NULL,TRUE),
(1,'Bayern Munich','Inter Milan',DATE_ADD(NOW(),INTERVAL 4 HOUR),'upcoming',0,0,NULL,TRUE),
(2,'Arsenal','Liverpool',DATE_ADD(NOW(),INTERVAL 20 HOUR),'upcoming',0,0,NULL,TRUE),
(3,'Barcelona','Atletico Madrid',DATE_ADD(NOW(),INTERVAL 26 HOUR),'upcoming',0,0,NULL,FALSE),
(4,'Boston Celtics','Miami Heat',DATE_ADD(NOW(),INTERVAL 3 HOUR),'upcoming',0,0,NULL,FALSE),
(5,'Jannik Sinner','Carlos Alcaraz',DATE_ADD(NOW(),INTERVAL 1 HOUR),'upcoming',0,0,NULL,TRUE),
(1,'Paris SG','Borussia Dortmund',NOW(),'live',2,1,67,TRUE),
(2,'Chelsea','Tottenham',NOW(),'live',1,1,54,FALSE),
(4,'Denver Nuggets','LA Lakers',NOW(),'live',74,70,38,FALSE);

INSERT INTO odds (match_id,market,selection,label,odd) VALUES
(1,'1X2','1','Manchester City',1.78),(1,'1X2','X','Draw',3.60),(1,'1X2','2','Real Madrid',4.20),(1,'OVER_UNDER','over','Over 2.5',1.92),(1,'BTTS','yes','Both teams to score',1.66),
(2,'1X2','1','Bayern Munich',1.65),(2,'1X2','X','Draw',3.80),(2,'1X2','2','Inter Milan',4.80),(2,'OVER_UNDER','over','Over 2.5',1.88),(2,'BTTS','yes','Both teams to score',1.70),
(3,'1X2','1','Arsenal',1.95),(3,'1X2','X','Draw',3.40),(3,'1X2','2','Liverpool',3.70),
(4,'1X2','1','Barcelona',1.55),(4,'1X2','X','Draw',4.10),(4,'1X2','2','Atletico Madrid',5.20),
(5,'1X2','1','Boston Celtics',1.62),(5,'1X2','2','Miami Heat',2.35),
(6,'1X2','1','Jannik Sinner',1.48),(6,'1X2','2','Carlos Alcaraz',2.60),
(7,'1X2','1','Paris SG',1.45),(7,'1X2','X','Draw',4.25),(7,'1X2','2','Borussia Dortmund',6.10),
(8,'1X2','1','Chelsea',2.10),(8,'1X2','X','Draw',3.30),(8,'1X2','2','Tottenham',3.45),
(9,'1X2','1','Denver Nuggets',1.35),(9,'1X2','2','LA Lakers',3.10);

INSERT INTO banners (title,subtitle,accent) VALUES
('Welcome bonus','Start with 1,000 demo credits','#43f5a2'),
('Live match center','Follow every second in real time','#4c8dff');
