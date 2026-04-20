<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Domainler (Zones)</h2>
    <form method="get" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Domain Ara..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Ara</button>
    </form>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Domain Adı</th>
                    <th>Durum</th>
                    <th>Süreç</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($zones)): ?>
                    <?php foreach ($zones as $zone): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($zone['name']) ?></strong></td>
                        <td>
                            <span class="badge bg-<?= $zone['status'] === 'active' ? 'success' : 'warning' ?>">
                                <?= htmlspecialchars($zone['status']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($zone['plan']['name'] ?? 'Bilinmiyor') ?></td>
                        <td>
                            <a href="/dns/<?= $zone['id'] ?>" class="btn btn-sm btn-info text-white">DNS Yönetimi</a>
                            <a href="/dns/<?= $zone['id'] ?>/bulk" class="btn btn-sm btn-secondary">Toplu İşlem</a>
                            <a href="/dns/<?= $zone['id'] ?>/missing" class="btn btn-sm btn-warning">Eksik Tarama</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">Kayıt bulunamadı.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if ($info['total_pages'] > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $info['total_pages']; $i++): ?>
                <li class="page-item <?= ($i == $info['page']) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>
