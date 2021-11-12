<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title><?= $data["title"] ?? "Default Title" ?></title>
        <link
            rel="shortcut icon"
            href="/images/icons/<?= $data["favicon"] ?? "favicon.ico" ?>"
            type="image/x-icon"
        >
    </head>
    <body>

        <?php require_once ROOT . "/app/Views/{$view}.php" ?>

    </body>
</html>
