-- ------------------------------------------------------------------
-- Base de données : anagramme
-- TP PHP - Vérificateur d'anagrammes (WAMP / MariaDB)
-- ------------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS anagramme
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE anagramme;

-- ------------------------------------------------------------------
-- Table des utilisateurs
-- ------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    pseudo     VARCHAR(30)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ------------------------------------------------------------------
-- Table des vérifications
-- user_id vaut NULL si la vérification n'est pas liée à un compte
-- ------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS verifications (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NULL,
    string1    VARCHAR(255) NOT NULL,
    string2    VARCHAR(255) NOT NULL,
    is_anagram TINYINT(1)   NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_verifications_user
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE
) ENGINE = InnoDB;
