<?php foreach ($menu as $item) : ?>
    <li class="nav-small-cap">
        <iconify-icon icon="solar:menu-dots-linear" class="mini-icon"></iconify-icon>
        <span class="hide-menu"><?= $item['name'] ?></span>
    </li>
    <li class="sidebar-item">

        <?php
        $hasArrrow = isset($item['children']) && !empty($item['children']);
        $linkClass = $hasArrrow ? 'sidebar-link has-arrow primary-hover-bg' : 'sidebar-link open-here';
        $url = $hasArrrow ? 'javascript:void(0)' : base_url('/' . $item['url']);
        ?>
        <a class="<?= $linkClass ?>" href="<?= $url ?>" aria-expanded="false">

            <iconify-icon icon="solar:<?= $item['icon'] ?>"></iconify-icon>
            <span class="hide-menu"><?= $item['name'] ?></span>
        </a>
        <ul aria-expanded="false" class="collapse first-level">
            <?php foreach ($item['children'] as $child) : ?>
                <?php
                $hasArrrow = isset($child['children']) && !empty($child['children']);
                $linkClass = $hasArrrow ? 'sidebar-link has-arrow primary-hover-bg' : 'sidebar-link open-here';
                $url = $hasArrrow ? 'javascript:void(0)' : base_url('/' . $child['url']);
                ?>
                <li class="sidebar-item">
                    <a href="<?= $url ?>" class="<?= $linkClass ?>" aria-expanded="false">
                        <span class="icon-small"></span>
                        <span class="hide-menu"><?= $child['name'] ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse two-level">
                        <?php foreach ($child['children'] as $grandchild) : ?>
                            <?php
                            $hasArrrow = isset($grandchild['children']) && !empty($grandchild['children']);
                            $linkClass = $hasArrrow ? 'sidebar-link has-arrow primary-hover-bg' : 'sidebar-link open-here';
                            $url = $hasArrrow ? 'javascript:void(0)' : base_url('/' . $grandchild['url']);
                            ?>
                            <li class="sidebar-item">
                                <a href="<?= $url ?>" class="<?= $linkClass ?>" aria-expanded="false">
                                    <span class="icon-small"></span>
                                    <span class="hide-menu"><?= $grandchild['name'] ?></span>
                                </a>
                                <ul aria-expanded="false" class="collapse three-level">
                                    <?php foreach ($grandchild['children'] as $greatgrandchild) : ?>
                                        <?php
                                        $hasArrrow = isset($greatgrandchild['children']) && !empty($greatgrandchild['children']);
                                        $linkClass = $hasArrrow ? 'sidebar-link has-arrow primary-hover-bg' : 'sidebar-link open-here';
                                        $url = $hasArrrow ? 'javascript:void(0)' : base_url('/' . $greatgrandchild['url']);
                                        ?>
                                        <li class="sidebar-item">
                                            <a href="<?= $url ?>" class="<?= $linkClass ?>" aria-expanded="false">
                                                <span class="icon-small"></span>
                                                <span class="hide-menu"><?= $greatgrandchild['name'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

            <?php endforeach; ?>
        </ul>

    </li>
<?php endforeach; ?>
