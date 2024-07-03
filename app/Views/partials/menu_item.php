<!-- app/Views/partials/menu_item.php -->
<li class="nav-item">
    <a class="nav-link" href="<?= base_url($menu['url']) ?>">
        <?= esc($menu['name']) ?>
    </a>
    <?php if (!empty($menu['children'])) : ?>
        <ul class="nav flex-column ml-3">
            <?php foreach ($menu['children'] as $child) : ?>
                <?= $this->include('partials/menu_item', ['menu' => $child]) ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</li>