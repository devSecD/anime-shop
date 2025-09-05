<section class="faq-section">
    <h2>Preguntas Frecuentes</h2>
    <?php
    $currentCategory = '';
    foreach($faqs as $faq):
        if ($faq['category_name'] !== $currentCategory):
            $currentCategory = $faq['category_name'];
    ?>
        <h3 class="faq-category"><?= htmlspecialchars($currentCategory) ?></h3>
    <?php endif; ?>
    <div class="faq-item">
        <h4 class="faq-question"><?= htmlspecialchars($faq['question']) ?></h4>
        <p class="faq-answer"><?= htmlspecialchars($faq['answer']) ?></p>
    </div>
    <?php endforeach; ?>
</section>
<?php if (in_array('cart', $assets)): ?>
    <script async src="/anime-shop/public/assets/js/components/cart.js" type="module"></script>
<?php endif; ?>