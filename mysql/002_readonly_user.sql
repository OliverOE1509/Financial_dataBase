CREATE USER IF NOT EXISTS 'readonly'@'%' IDENTIFIED BY 'readonly';
GRANT SELECT ON `FinansDB`.* TO 'readonly'@'%';
FLUSH PRIVILEGES;