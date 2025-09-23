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
                            <th>Email</th>
                            <th>
                                Con <span class="badge-registered"><i class="fa-solid fa-user"></i></span> 
                                ó 
                                Sin <span class="badge-guest"><i class="fa-solid fa-envelope"></i></span>
                                cuenta
                            </th>
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
                                    <td data-label="Email"><?= htmlspecialchars($subscriber['email']) ?></td>
                                    <td data-label="Tipo suscriptor">
                                        <?php if ($subscriber['is_registered']): ?>
                                            <span class="badge-registered" title="Tiene cuenta en Anime Shop">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-guest" title="Suscriptor sin cuenta">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                        <?php endif; ?>
                                        <span class="label-mobile">
                                            <?= $subscriber['is_registered'] ? 'Registrado' : 'Invitado' ?>
                                        </span>
                                    </td>
                                    <td data-label="Fecha de suscripción"><?= \App\helpers\DateHelper::formatShort($subscriber['subscribed_at']) ?></td>
                                    <td data-label="Acciones">
                                        <a href="/anime-shop/public/admin/newsletter/delete?id=<?= $subscriber['email'] ?>" 
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

                <?php
                    include __DIR__ . '/../../../view/admin/components/pagination.php';
                ?>

            </div>
        </main>
    </div>
    <script async src="/anime-shop/public/assets/js/components/modal-delete.js" type="module"></script>
</body>
</html>
