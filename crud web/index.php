<?php
session_start();
require_once 'CompetitionController.php';
require_once 'database.php';
require_once 'twig.php';

function generateCsrfToken(): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

$controller = new CompetitionController($pdo);
$action = $_GET['action'] ?? 'read';

switch ($action) {
    case 'generateFixtures':
        $csrfToken = $_GET['csrf_token'] ?? '';
        if ($csrfToken !== $_SESSION['csrf_token']) {
            die('Échec de la vérification CSRF.');
        }
        $controller->generateFixtures($csrfToken);
        header('Location: index.php?action=read');
        exit;
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $controller->create($_POST, $csrfToken);
            header('Location: index.php?action=read');
            exit;
        } else {
            $csrfToken = generateCsrfToken();
            echo $twig->render('competition/create.html.twig', ['csrf_token' => $csrfToken]);
        }
        break;
    case 'read':
        $competitions = $controller->readAll();
        $csrfToken = generateCsrfToken();
        echo $twig->render('competition/read.html.twig', ['competitions' => $competitions, 'csrf_token' => $csrfToken]);
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            $controller->update($_GET['id'], $_POST, $csrfToken);
            header('Location: index.php?action=read');
            exit;
        } else {
            $competition = $controller->getById($_GET['id']);
            $csrfToken = generateCsrfToken();
            echo $twig->render('competition/update.html.twig', ['competition' => $competition, 'csrf_token' => $csrfToken]);
        }
        break;
    case 'delete':
        if (isset($_GET['id'])) {
            $csrfToken = $_GET['csrf_token'] ?? '';
            $controller->delete($_GET['id'], $csrfToken);
        }
        header('Location: index.php?action=read');
        exit;
        break;

    default:
        echo "Action non reconnue.";
        break;
}
?>