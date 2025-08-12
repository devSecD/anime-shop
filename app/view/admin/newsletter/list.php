<?php
    use App\Helpers\SessionHelper;
    SessionHelper::start();
    $user = SessionHelper::getUser() ;
?>
<!DOCTYPE html>
<html lang="es">
<?php include $html_head ?>
<body>
    <div class="admin-dashboard">
        <?php include $sidebar ?>
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Panel de administración: Suscriptores</h1>
                <p>Bienvenido, <?= $user["name"] ?></p>
            </header>

            <div style="overflow-x: auto;">
                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Fecha de suscripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($subscribers)): ?>
                            <tr>
                                <td colspan="4" class="text-center no-products">No hay suscriptores registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($subscribers as $subscriber): ?>
                                <tr>
                                    <td><?= htmlspecialchars($subscriber['id']) ?></td>
                                    <td><?= htmlspecialchars($subscriber['email']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($subscriber['created_at'])) ?></td>
                                    <td>
                                        <a href="/anime-shop/public/admin/newsletter/delete?id=<?= $subscriber['id'] ?>" 
                                           data-confirm="¿Deseas eliminar este suscriptor?" 
                                           class="action-delete btn-confirm-delete" 
                                           title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <?php include $modalConfirmDelete ?>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script async src="/anime-shop/public/assets/js/components/modal-delete.js" type="module"></script>
</body>
</html>
