<h1>Pages</h1>

<?php if (session()->getFlashdata('message')): ?>
    <div><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<a href="/t/<?= TenantContext::require() ?>/pages/new">Create New Page</a>

<?php if (empty($pages)): ?>
    <p>No pages found.</p>
<?php else: ?>
    <ul>
        <?php foreach ($pages as $page): ?>
            <li>
                <?= esc($page['title']) ?>
                <a href="/t/<?= TenantContext::require() ?>/pages/<?= $page['id'] ?>/edit">Edit</a>
                <form action="/t/<?= TenantContext::require() ?>/pages/<?= $page['id'] ?>" method="POST" style="display:inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
