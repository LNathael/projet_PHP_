<?php
// Vérification si ROOT_PATH est défini
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2)); // Définit le chemin absolu à partir de la racine du projet
}

// Charger la configuration de la base de données
$config = include ROOT_PATH . '/config.php';

try {
    // Connexion à la base de données
    $pdo = new PDO(
        'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8',
        $config['db_user'],
        $config['db_password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active le mode exception pour les erreurs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Définit le mode de récupération par défaut
} catch (PDOException $e) {
    // En cas d'erreur, affiche un message personnalisé et journalise l'erreur
    error_log($e->getMessage(), 3, ROOT_PATH . '/logs/db_errors.log');
    die('Erreur : impossible de se connecter à la base de données. Veuillez réessayer plus tard.');
}
?>