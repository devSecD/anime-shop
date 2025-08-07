<?php
use App\Helpers\FormHelper;
?>
<form method="POST" id="<?= $idForm ?>" action="<?= $urlForm ?>" class="form-container form-wide" novalidate>
    <?php if($page === 'updateProduct'): ?>
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
    <?php endif; ?>

    <h2><?= $titleForm ?></h2>

    <div class="form-row">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" name="name" value="<?= FormHelper::getFieldValue('name', $product) ?>" required>
        </div>

        <div class="form-group">
            <label for="price">Precio:</label>
            <input type="number" step="0.01" name="price" value="<?= FormHelper::getFieldValue('price', $product) ?>" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group optional">
            <label for="price_discounted">Precio con descuento: <span class="optional-tag">(opcional)</span></label>
            <input type="number" step="0.01" name="price_discounted" value="<?= FormHelper::getFieldValue('price_discounted', $product) ?>">
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" value="<?= FormHelper::getFieldValue('stock', $product) ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Descripción:</label>
        <textarea name="description" rows="4" required><?= FormHelper::getFieldValue('description', $product) ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="category">Categoría:</label>
            <select name="category_id" id="category" required>
                <option value="">-- Selecciona una categoría --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_id'] ?>"
                        <?= isset($product['category_id']) && $product['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="brand">Marca:</label>
            <select name="brand_id" id="brand" required>
                <option value="">-- Selecciona una marca --</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand['brand_id'] ?>"
                        <?= isset($product['brand_id']) && $product['brand_id'] == $brand['brand_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['name']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group file-upload-group">
            <?php if (!empty($product['image'])): ?>
                <p>Imagen actual:</p>
                <img src="/anime-shop/public/assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="Imagen del producto" id="preview">
            <?php endif; ?>
            <label for="image" class="file-upload-label">
                <i class="fa-solid fa-upload"></i> Seleccionar imagen
            </label>
            <input type="file" name="image" id="image" class="file-upload-input" accept="image/*">
            <span id="file-name" class="file-name-preview">Ningún archivo seleccionado</span>
        </div>

        <div class="form-group checkbox-group optional">
            <label>
                <input type="checkbox" name="is_on_sale" value="1" <?= FormHelper::isCheckboxChecked('is_on_sale', $product) ?>>
                ¿Producto en oferta? <span class="optional-tag">(opcional)</span>
            </label>
            <label>
                <input type="checkbox" name="is_preorder" value="1"  <?= FormHelper::isCheckboxChecked('is_preorder', $product) ?>>
                ¿Es preventa? <span class="optional-tag">(opcional)</span>
            </label>
        </div>
    </div>

    <button type="submit" id="<?= $idButtonForm ?>" class="btn-primary">
        <span class="btn-text"><?= $textButton ?></span>
        <span class="spinner hidden"></span>
    </button>

    <script>
    document.getElementById('image').addEventListener('change', function (event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
            // preview.style.display = 'block';
        }
    });
    </script>

</form>