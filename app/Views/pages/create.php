<h1>Create New Page</h1>

<?php if (session()->has('errors')): ?>
    <ul>
        <?php foreach (session('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/t/<?= TenantContext::require() ?>/pages" method="POST">
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
    </div>
    
    <div>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10"></textarea>
    </div>
    
    <div>
        <button type="submit">Create Page</button>
        <a href="/t/<?= TenantContext::require() ?>/pages">Cancel</a>
    </div>
</form>
