-- This script is used to upgrade the database schema by adding new columns to existing tables.

-- Ajout de la colonne "nbhcourstd" à la table "matiereenseignee"
DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'matiereenseignee'
          AND column_name = 'nbhcourstd'
    ) THEN
        ALTER TABLE matiereenseignee ADD nbhcourstd NUMERIC(5,2);
    ELSE
        RAISE NOTICE 'Column "nbhcourstd" exists.';
    END IF;
END $$;