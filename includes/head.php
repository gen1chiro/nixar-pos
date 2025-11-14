<!DOCTYPE html>
<html lang="en">
<head>
    <!-- toby was here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $PageTitle ?></title>
    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" href="/nixar-pos/public/assets/img/favicon.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/a0f7dcf96e.js" crossorigin="anonymous"></script>
    <!-- Append modified timestamp after js/css file path - 
    fix browser caching issues that prevents the js/css file to update --> 
    <link rel="stylesheet" href='<?=$CssPath ?>?v=<?=filemtime($CssPath)?>'>
    <script defer src="<?= $JSPath ?>?v=<?=filemtime($JSPath)?>"></script>
</head>
<body>