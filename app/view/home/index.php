<?php
    use App\Helpers\SessionHelper;

    SessionHelper::start();
    SessionHelper::regenerate();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/css/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../public/assets/css/index.css">
    <link rel="stylesheet" href="/anime-shop/public/assets/css/components/alerts.css">
    <title>Home</title>
</head>
<body>
    <header>
        <?php include __DIR__ . '/../components/_navbar_main.php'; ?>
    </header>
    <?php include __DIR__ . '/../products/_catalog_partial.php'; ?>
    <?php include __DIR__ . '/../components/_footer.php'; ?>
    <script src="/anime-shop/public/assets/js/newsletter.js"></script>
    <script src="/anime-shop/public/assets/js/components/userDropdown.js" defer></script>
    <script type="module">
        import { initBurgerMenu } from '/anime-shop/public/assets/js/components/burgerMenu.js';
        initBurgerMenu();
    </script>
</body>
</html>