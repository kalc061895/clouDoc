
<?php foreach ($menu as $item) : ?>

    <li class="sidebar-item">
        <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
            <span class="rounded-3">
                <iconify-icon icon="solar:<?= $item['icon'] ?>" class="ti"></iconify-icon>
            </span>
            <span class="hide-menu"><?= $item['name'] ?></span>
        </a>

        <ul aria-expanded="false" class="collapse first-level">
            <?php foreach ($item['children'] as $child) : ?>
                <?php
                $hasArrrow = isset($child['children']) && !empty($child['children']);
                $linkClass = $hasArrrow ? 'sidebar-link has-arrow' : 'sidebar-link ';
                $url = $hasArrrow ? 'javascript:void(0)' : base_url('/' . $child['url']);
                ?>
                <li class="sidebar-item">
                    <a href="<?= $url ?>" class="<?= $linkClass ?>">
                        <iconify-icon icon="solar:<?= $child['icon'] ?>"></iconify-icon>
                        <span class="hide-menu"><?= $child['name'] ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse second-level">
                        <?php foreach ($child['children'] as $grandchild) : ?>
                            <?php
                            $hasArrrow = isset($grandchild['children']) && !empty($grandchild['children']);
                            $linkClass = $hasArrrow ? 'sidebar-link has-arrow' : 'sidebar-link ';
                            $url = $hasArrrow ? 'javascript:void(0)' : base_url('/' . $grandchild['url']);
                            ?>
                            <li class="sidebar-item">
                                <a href="<?= $url ?>" class="<?= $linkClass ?>">
                                    <iconify-icon icon="solar:<?= $grandchild['icon'] ?>"></iconify-icon>
                                    <span class="hide-menu"><?= $grandchild['name'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>

    </li>
<?php endforeach; ?>