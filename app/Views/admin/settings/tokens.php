<h2 class="mb-4">API Token Yönetimi</h2>

<div class="card mb-4">
    <div class="card-header">Yeni Token Ekle</div>
    <div class="card-body">
        <form action="/settings/tokens" method="post" class="d-flex align-items-end">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="col-3 me-2">
                <label class="form-label">Hesap Adı</label>
                <input type="text" name="name" class="form-control" required placeholder="Müşteri A vb.">
            </div>
            <div class="col-7 me-2">
                <label class="form-label">Cloudflare API Token</label>
                <input type="password" name="token" class="form-control" required placeholder="Token giriniz...">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-success w-100">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Kayıtlı Tokenlar</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Hesap Adı</th>
                    <th>Token (Gizli)</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tokens)): ?>
                    <?php foreach ($tokens as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['name']) ?></td>
                        <td><?= str_repeat('*', 20) . substr(htmlspecialchars($t['token']), -4) ?></td>
                        <td>
                            <form action="/settings/tokens/delete" method="post" class="d-inline" onsubmit="return confirm('Emin misiniz?');">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">Henüz token eklenmedi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
