<?php
require_once 'CompetitionController.php';
require_once 'database.php';
require_once 'twig.php';

$controller = new CompetitionController($pdo);
$action = $_GET['action'] ?? 'read';

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create($_POST);
            header('Location: index.php?action=read');
            exit;
        } else {
            echo $twig->render('competition/create.html.twig');
        }
        break;
    case 'read':
        $competitions = $controller->readAll();
        echo $twig->render('competition/read.html.twig', ['competitions' => $competitions]);
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($_GET['id'], $_POST);
            header('Location: index.php?action=read');
            exit;
        } else {
            $competition = $controller->getById($_GET['id']);
            echo $twig->render('competition/update.html.twig', ['competition' => $competition]);
        }
        break;
    case 'delete':
        if (isset($_GET['id'])) {
            $controller->delete($_GET['id']);
        }
        header('Location: index.php?action=read');
        exit;
        break;

    default:
        echo "Action non reconnue.";
        break;
}
?>
