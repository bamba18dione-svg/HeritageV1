
DROP TABLE IF EXISTS copie_examen;

CREATE TABLE IF NOT EXISTS copie_examen (
    id SERIAL PRIMARY KEY,
    date_depot TIMESTAMP NOT NULL,
    date_limite TIMESTAMP NOT NULL,
    note_brute NUMERIC(4, 2) NOT NULL CHECK (note_brute >= 0 AND note_brute <= 20),
    note_finale NUMERIC(4, 2) NOT NULL CHECK (note_finale >= 0 AND note_finale <= 20),
    penalite_appliquee BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO copie_examen (date_depot, date_limite, note_brute, note_finale, penalite_appliquee)
VALUES ('2026-09-01 10:00:00', '2026-09-05 23:59:59', 15.50, 15.50, FALSE);

select * from copie_examen;