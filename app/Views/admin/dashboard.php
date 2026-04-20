<h2 class="mb-4">Dashboard</h2>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Aktif Cloudflare Hesabı</h5>
                <p class="card-text fs-4">
                    <?php 
                    $activeName = 'Seçili Değil';
                    if (!empty($tokens)) {
                        foreach ($tokens as $t) {
                            if ($t['id'] == $activeTokenId) {
                                $activeName = htmlspecialchars($t['name']);
                                break;
                            }
                        }
                    }
                    echo $activeName;
                    ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Toplam Domain Sayısı</h5>
                <p class="card-text fs-4"><?= (int)$zonesCount ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Cloudflare Hesabı Değiştir
    </div>
    <div class="card-body">
        <form method="get" action="/dashboard" class="d-flex">
            <select name="token_id" class="form-select me-2" required>
                <?php foreach ($tokens as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= $t['id'] == $activeTokenId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Değiştir</button>
        </form>
    </div>
</div>
