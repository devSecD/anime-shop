<?php
    use App\Helpers\SessionHelper;
    SessionHelper::start();
    $user = SessionHelper::getUser() ;
?>
<!DOCTYPE html>
<html lang="es">
<?php include $html_head; ?>
<body>
    <div class="admin-dashboard">
        <?php include $sidebar; ?>
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Panel de administración: Usuarios</h1>
                <p>Bienvenido, <?= $user["name"] ?></p>
            </header>

            <div style="overflow-x: auto;">
                <table class="admin-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol principal</th>
                            <th>Roles adicionales</th>
                            <th>Fecha de registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center no-products">No hay usuarios registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td data-label="ID"><?= htmlspecialchars($user['user_id']) ?></td>
                                    <td data-label="Nombre"><?= htmlspecialchars($user['name']) ?></td>
                                    <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
                                    <td data-label="Teléfono"><?= htmlspecialchars($user['phone'] ?? '-') ?></td>
                                    <td data-label="Rol principal"><?= htmlspecialchars($user['role']) ?></td>
                                    <td data-label="Roles adicionales"><?= htmlspecialchars($user['roles']) ?></td>
                                    <td data-label="Fecha de registro"><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php
                    $queryParams = [];
                    if ($role) $queryParams['role'] = $role;

                    include __DIR__ . '/../../../view/admin/components/pagination.php';
                ?>

            </div>
        </main>
    </div>
</body>
</html>