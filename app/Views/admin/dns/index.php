<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= htmlspecialchars($zone['name']) ?> - DNS Kayıtları</h2>
    <a href="/domains" class="btn btn-secondary">Geri</a>
</div>

<div class="card mb-4">
    <div class="card-header">Yeni Kayıt Ekle</div>
    <div class="card-body">
        <form action="/dns/<?= $zone['id'] ?>/create" method="post" class="row align-items-end g-3">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="col-md-2">
                <label class="form-label">Kayıt Tipi</label>
                <select name="type" class="form-select" required>
                    <option value="A">A</option>
                    <option value="AAAA">AAAA</option>
                    <option value="CNAME">CNAME</option>
                    <option value="TXT">TXT</option>
                    <option value="MX">MX</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ad (Name)</label>
                <input type="text" name="name" class="form-control" required placeholder="@ veya www">
            </div>
            <div class="col-md-3">
                <label class="form-label">İçerik (Target/IP)</label>
                <input type="text" name="content" class="form-control" required>
            </div>
            <div class="col-md-1">
                <label class="form-label">Proxy</label>
                <select name="proxied" class="form-select">
                    <option value="1">Açık</option>
                    <option value="0">Kapalı</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label">TTL</label>
                <select name="ttl" class="form-select">
                    <option value="1">Auto</option>
                    <option value="3600">1 Saat</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Ekle</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover m-0">
            <thead>
                <tr>
                    <th>Tip</th>
                    <th>Ad</th>
                    <th>İçerik</th>
                    <th>Proxy</th>
                    <th>TTL</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['type']) ?></td>
                    <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
                    <td><span class="text-truncate d-inline-block" style="max-width:300px;"><?= htmlspecialchars($r['content']) ?></span></td>
                    <td><?= $r['proxied'] ? '<span class="badge bg-warning"><i class="bi bi-cloud-fill"></i> Proxied</span>' : 'Sadece DNS' ?></td>
                    <td><?= $r['ttl'] == 1 ? 'Auto' : $r['ttl'] ?></td>
                    <td>
                        <form action="/dns/<?= $zone['id'] ?>/delete" method="post" class="d-inline" onsubmit="return confirm('Kayıt silinecek, emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="record_id" value="<?= $r['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
