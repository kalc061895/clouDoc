<span class="asis-user-avatar <?= esc($avatarClass ?? '', 'attr') ?>" aria-hidden="true">
    <span><?= esc($perfilAsistencia['iniciales']) ?></span>
    <?php if ($perfilAsistencia['foto']): ?><img src="<?= esc($perfilAsistencia['foto'], 'attr') ?>" alt="" class="asis-user-photo" loading="lazy"><?php endif ?>
</span>
