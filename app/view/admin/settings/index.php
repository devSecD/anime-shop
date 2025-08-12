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
        
        <main class="main-content" data-page="<?= $page ?? '' ?>">
            <header class="dashboard-header">
                <h1>Panel de administración: Configuración general</h1>
                <p>Bienvenido, <?= $user["name"] ?></p>
            </header>

            <form id="updateConfigurationForm" action="/anime-shop/public/admin/setting/index/update" method="POST" class="form-container form-wide">
                <div style="overflow-x: auto;">

                    <table class="admin-products-table">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filteredSettings as $setting): ?>
                                <tr>
                                    <td><?= htmlspecialchars($setting['key']) ?></td>
                                    <td>
                                        <div class="form-group">
                                            <input 
                                                type="text" 
                                                name="settings[<?= htmlspecialchars($setting['key']) ?>]" 
                                                value="<?= htmlspecialchars($setting['value']) ?>" 
                                            >
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td>Logo</td>
                                <td>
                                    <div class="form-group file-upload-group">
                                        <?php if (!empty($settingAssoc['logo_path'])): ?>
                                            <p style="margin-bottom: var(--small-spacing); font-size: var(--font-size-small); color: var(--secondary-text-color);">Logo actual:</p>
                                            <img src="/anime-shop/public/assets/images/logo/<?= $settingAssoc['logo_path'] ?>" alt="Logo" id="preview">
                                        <?php endif; ?>
                                        
                                        <label for="image" class="file-upload-label">
                                            <i class="fa-solid fa-upload"></i> Seleccionar Logo
                                        </label>
                                        <input type="file" name="logo_path" id="image" class="file-upload-input" accept="image/*">
                                        <span id="file-name" class="file-name-preview">Ningún archivo seleccionado</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>  

                <button type="submit" id="btn-add-setting" class="btn-primary">
                    <span class="btn-text">Guardar cambios</span>
                    <span class="spinner hidden"></span>
                </button>

            </form>
        </main>
    </div>
    <script async type="module" src="/anime-shop/public/assets/js/main.js"></script>
</body>
</html>