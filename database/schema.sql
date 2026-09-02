-- ============================================================
-- Schéma de la base de données : Gestion des copies d'examen
-- Fichier : database/schema.sql
-- ============================================================

-- Suppression de la table si elle existe déjà (pour réinitialisation)
DROP TABLE IF EXISTS copie_examen;

-- Création de la table pour stocker les copies d'examen
CREATE TABLE IF NOT EXISTS copie_examen (
    id SERIAL PRIMARY KEY,
    date_depot TIMESTAMP NOT NULL,
    date_limite TIMESTAMP NOT NULL,
    note_brute NUMERIC(4, 2) NOT NULL CHECK (note_brute >= 0 AND note_brute <= 20),
    note_finale NUMERIC(4, 2) NOT NULL CHECK (note_finale >= 0 AND note_finale <= 20),
    penalite_appliquee BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- Données de test
-- ============================================================

-- Insertion d'une ligne de test
INSERT INTO copie_examen (date_depot, date_limite, note_brute, note_finale, penalite_appliquee)
VALUES ('2026-09-01 10:00:00', '2026-09-05 23:59:59', 15.50, 15.50, FALSE);

-- Consultation de la ligne de test
-- SELECT * FROM copie_examen;
