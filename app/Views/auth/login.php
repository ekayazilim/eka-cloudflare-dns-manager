<div class="text-center mb-4">
    <h4>Eka DNS Yönetimi</h4>
    <p class="text-muted">Lütfen giriş yapın</p>
</div>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form action="/login" method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <div class="mb-3">
        <label class="form-label">E-Posta</label>
        <input type="email" name="email" class="form-control" required value="admin@admin.com">
    </div>
    <div class="mb-3">
        <label class="form-label">Şifre</label>
        <input type="password" name="password" class="form-control" required value="123456">
    </div>
    <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
</form>
