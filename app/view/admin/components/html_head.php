<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlentities($title) ?></title>
    <link rel="stylesheet" href="/anime-shop/public/assets/css/variables.css">
    <link rel="stylesheet" href="/anime-shop/public/assets/css/components/dashboard.css">
    <link rel="stylesheet" href="/anime-shop/public/assets/css/components/alerts.css">
    <?php if (isset($assets) && is_array($assets) && !empty($assets) && in_array('form', $assets)): ?>
        <link rel="stylesheet" href="/anime-shop/public/assets/css/form.css">
        <link rel="stylesheet" href="/anime-shop/public/assets/css/components/spinner.css">
    <?php endif; ?>
    <?php if($page === 'admin_products_list' || $page === 'admin_orders_list' || $page === 'admin_users_list' || $page === 'admin_newsletter_list' || $page === 'updateConfiguration'): ?>
        <link rel="stylesheet" href="/anime-shop/public/assets/css/components/admin-products.css">
        <link rel="stylesheet" href="/anime-shop/public/assets/css/components/modal-delete.css">
    <?php endif; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto&display=swap">
</head>