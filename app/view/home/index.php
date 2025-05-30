<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/css/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../public/assets/css/index.css">
    <title>Home</title>
</head>
<body>
    <header>
        <?php include __DIR__ . '/../components/_navbar_main.php'; ?>
        <?php include __DIR__ . '/../components/_navbar_secondary.php'; ?>
    </header>
    <?php include __DIR__ . '/../products/_catalog_partial.php'; ?>
    <?php include __DIR__ . '/../components/_footer.php'; ?>

    <script src="../public/assets/js/newsletter.js"></script>
</body>
</html>