<?php
require 'vendor/autoload.php';

$exclude = [
    'information_schema',
    'mysql',
    'performance_schema'
];

$pdo = new PDO('mysql:host=localhost', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$query = $pdo->query('SHOW DATABASES');
$databases = $query->fetchAll();

foreach ($databases as $database) {
    $database_name = $database->Database;
    if(!in_array($database_name, $exclude)) {
        try {
            $dump = new \Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=' . $database_name, 'root', 'root');
            $dump->start($database_name . '.sql');
        } catch (Exception $e) {
            echo 'Impossible de faire la sauvegarde de la base' . $database_name . ' : ' . $e->getMessage();
        }
    }
}

echo "La sauvegarde a bien fonctionnée\n";