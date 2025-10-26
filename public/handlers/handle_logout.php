<?php 
    include_once __DIR__ . '/../../includes/config/_init.php';
    SessionManager::destroy();
    header('Location: /nixar-pos/public/index.php');
    exit;
?>