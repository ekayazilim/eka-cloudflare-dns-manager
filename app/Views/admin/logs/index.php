<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Sistem Logları</h2>
    <form action="/settings/logs/clear" method="post" onsubmit="return confirm('Tüm loglar silinecek. Emin misiniz?');">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="btn btn-danger">Logları Temizle</button>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-bordered m-0" style="font-size: 13px;">
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><pre class="m-0 text-wrap"><?= htmlspecialchars($log) ?></pre></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td class="text-center p-3">Log kaydı bulunamadı.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
