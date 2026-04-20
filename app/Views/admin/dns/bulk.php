<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><?= htmlspecialchars($zone['name']) ?> - Toplu DNS Ekleme</h2>
    <a href="/domains" class="btn btn-secondary">Geri</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="/dns/<?= $zone['id'] ?>/bulk" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Alt Alan Adları (Subdomains)</label>
                        <textarea name="subdomains" class="form-control" rows="10" placeholder="Her satıra bir tane yazın. Örn:&#10;@&#10;www&#10;mail&#10;ftp" required></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kayıt Tipi</label>
                        <select name="type" class="form-select">
                            <option value="A">A</option>
                            <option value="CNAME">CNAME</option>
                            <option value="TXT">TXT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">İçerik (IP veya Hedef)</label>
                        <input type="text" name="content" class="form-control" required placeholder="192.168.1.1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proxy Durumu</label>
                        <select name="proxied" class="form-select">
                            <option value="1">Cloudflare (Proxied)</option>
                            <option value="0">Sadece DNS (DNS Only)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-4">Toplu Ekle / Güncelle</button>
                </div>
            </div>
        </form>
    </div>
</div>
