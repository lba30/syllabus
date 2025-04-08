<?php

/**
 * Fichier de configuration des routes.
 */

const AVAILABLE_ROUTES = [
    'home' => 'homeController.php',
    'ue' => 'ueController.php',
    'modifierue' => 'modifierueController.php',
    'modifierecue' => 'modifierecueController.php',
    'ajouterecue' => 'ajouterecueController.php',
    'ajouterue' => 'ajouterueController.php',
    'responsable' => 'responsableController.php',
    'competence' => 'competenceController.php',
    'modifiercompetence' => 'modifiercompetenceController.php',
    'anneescolaire' => 'anneescolaireController.php',
    'cycleenseignement' => 'cycleenseignementController.php',
    'cycleenseignementannee' => 'cycleenseignementanneeController.php',
    'periodedeformation' => 'periodedeformationController.php',
    'departement' => 'departementController.php',
    'option' => 'optionController.php',
    'periodedeformationannee' => 'periodedeformationanneeController.php',
    'login' => 'loginController.php',
    'logout' => 'logoutController.php',
    'profile' => 'profileController.php',
    'config' => 'configController.php'
  ];

const PROTECTED_ROUTES  = [
  'periodedeformationannee' => 'administrateur',
  'ajouterue' => 'administrateur',
  'competence' => 'administrateur',
  'modifiercompetence' => 'administrateur',
  'anneescolaire' => 'administrateur',
  'cycleenseignement' => 'administrateur',
  'cycleenseignementannee' => 'administrateur',
  'periodedeformation' => 'administrateur',
  'departement' => 'administrateur',
  'option' => 'administrateur',
  'ajouterecue' => 'administrateur',
  'responsable' => 'administrateur',
  'modifierue' => 'responsable',
  'modifierecue' => 'responsable',
  'profile' => 'responsable'
];

const DEFAULT_ROUTE = AVAILABLE_ROUTES['home'];
