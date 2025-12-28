<h1>Edit Page</h1>

<?php if (session()->has('errors')): ?>
    <ul>
        <?php foreach (session('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (session()->has('message')): ?>
    <div><?= session('message') ?></div>
<?php endif; ?>

<form action="/t/<?= TenantContext::require() ?>/pages/<?= $page['id'] ?>" method="POST">
    <input type="hidden" name="_method" value="PUT">
    
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= esc($page['title']) ?>" required>
    </div>
    
    <div>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10"><?= esc($page['content']) ?></textarea>
    </div>
    
    <div>
        <button type="submit">Update Page</button>
        <a href="/t/<?= TenantContext::require() ?>/pages">Cancel</a>
    </div>
</form>

<hr>

<h3>Danger Zone</h3>
<form action="/t/<?= TenantContext::require() ?>/pages/<?= $page['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?')">
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit">Delete Page</button>
</form>
