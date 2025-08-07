<!DOCTYPE html>
<html lang="es">
    <?php include $html_head ?>
<body>
    <div class="admin-dashboard">

        <?php include $sidebar ?>
        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
            <h1>Panel de Administración</h1>
            <p>Bienvenido, Admin</p>
            </header>

            <!-- Summary Cards -->
            <section class="dashboard-cards">
            <div class="card">
                <h3>120</h3>
                <p>Productos</p>
            </div>
            <div class="card">
                <h3>35</h3>
                <p>Órdenes nuevas</p>
            </div>
            <div class="card">
                <h3>10</h3>
                <p>Suscriptores</p>
            </div>
            <div class="card">
                <h3>5</h3>
                <p>En espera</p>
            </div>
            </section>

            <!-- Products Table -->
            <section class="dashboard-table">
            <h2>Productos recientes</h2>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>#001</td>
                    <td>Naruto Figure</td>
                    <td>$29.99</td>
                    <td>15</td>
                    <td><button class="btn-action">Editar</button></td>
                </tr>
                <tr>
                    <td>#002</td>
                    <td>Attack Titan T-Shirt</td>
                    <td>$19.99</td>
                    <td>40</td>
                    <td><button class="btn-action">Editar</button></td>
                </tr>
                </tbody>
            </table>
            </section>
        </main>
    </div>
</body>
</html>