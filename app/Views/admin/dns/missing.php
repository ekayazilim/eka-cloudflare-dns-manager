<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= htmlspecialchars($zone['name']) ?> - Eksik Kayıt Tarama</h2>
    <a href="/domains" class="btn btn-secondary">Geri</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Kontrol Edilecek Adresler</div>
            <div class="card-body">
                <form action="/dns/<?= $zone['id'] ?>/missing/scan" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="scan">
                    <textarea name="check_list" class="form-control mb-3" rows="8"><?= htmlspecialchars($checkList) ?></textarea>
                    <button type="submit" class="btn btn-primary w-100">Tara</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Tarama Sonuçları</div>
            <div class="card-body">
                <?php if (isset($missing) && !empty($missing)): ?>
                    <div class="alert alert-warning">
                        <strong><?= count($missing) ?></strong> adet kayıt eksik bulundu!
                    </div>
                    <form action="/dns/<?= $zone['id'] ?>/missing/create" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <table class="table table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th style="width: 50px;"><input type="checkbox" id="checkAll" checked></th>
                                    <th>Eksik Domain</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($missing as $m): ?>
                                <tr>
                                    <td><input type="checkbox" name="missing_records[]" value="<?= htmlspecialchars($m) ?>" checked class="checkItem"></td>
                                    <td><?= htmlspecialchars($m) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="mb-3">
                            <label class="form-label">Hedef IP (A Kaydı Olarak Eklenecek)</label>
                            <input type="text" name="target_ip" class="form-control" required placeholder="1.2.3.4">
                        </div>
                        <button type="submit" class="btn btn-success">Seçilenleri Oluştur</button>
                    </form>
                    <script>
                        document.getElementById('checkAll').addEventListener('change', function(e) {
                            var checkboxes = document.querySelectorAll('.checkItem');
                            for (var i = 0; i < checkboxes.length; i++) {
                                checkboxes[i].checked = e.target.checked;
                            }
                        });
                    </script>
                <?php elseif (isset($missing)): ?>
                    <div class="alert alert-success">Tüm kayıtlar tam. Eksik bulunamadı.</div>
                <?php else: ?>
                    <div class="text-muted">Tarama yapmak için yandaki formu kullanın.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
