<?php

/**
 * Fichier de configuration de la base de données.
 */

const DB_CONFIG_PROD = [
  'host'     => 'sql2.mines-ales.fr',
  'port'     => '5433',
  'dbname'   => 'syllabus',
  'username' => 'syllabus',
  'password' => 'xxx'
];

const DB_CONFIG_PREPROD = [
  'host'     => 'sql2.mines-ales.fr',
  'port'     => '5433',
  'dbname'   => 'syllabus_dev',
  'username' => 'syllabus_dev',
  'password' => 'xxx'
];

const DB_CONFIG_DEV = [
  'host'     => 'localhost',
  'port'     => '5432',
  'dbname'   => 'syllabus',
  'username' => 'syllabus',
  'password' => 'xxx'
];
