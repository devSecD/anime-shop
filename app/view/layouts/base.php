<!DOCTYPE html>
<html lang="es">
<head>
    <?php $assets = $assets ?? []; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Anime Shop' ?></title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/fontawesome-free-6.7.2-web/css/all.min.css">
    <?php if (in_array('form', $assets)): ?>
        <link rel="stylesheet" href="../assets/css/form.css">
    <?php endif; ?>
    <link rel="stylesheet" href="../assets/css/components/alerts.css">
</head>
<body>
    <?php include __DIR__ . '/../components/_navbar_main.php'; ?>

    <main style="min-height: 80vh;" data-page="<?= $page ?? '' ?>"><?php include $content; ?></main>

    <?php include __DIR__ . '/../components/_footer.php'; ?>
</body>
</html>