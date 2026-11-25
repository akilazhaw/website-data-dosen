<?php
/**
 * SISTEM INFORMASI MANAJEMEN DATA DOSEN (Versi PHP Mandiri)
 * Pemrograman Berbasis Website Lanjutan
 * 
 * Atribut:
 * 1. NIDN (Nomor Induk Dosen Nasional)
 * 2. Nama Lengkap & Gelar
 * 3. Program Studi
 * 4. Alamat Lengkap
 */

// ==========================================
// 1. KONEKSI & DATABASE (SQLite / MySQL)
// ==========================================
$db_driver  = 'sqlite'; // 'sqlite' atau 'mysql'
$mysql_host = '127.0.0.1';
$mysql_port = '3306';
$mysql_user = 'root';
$mysql_pass = '';
$mysql_db   = 'db_dosen';

// Data Awal Standar
$default_samples = [
    ['0005078504', 'Keonho, S.T., M.Kom', 'Rekayasa Perangkat Lunak', 'Jl. jalan kita berdua'],
    ['0011129002', 'Mingyu Kim, M.Kom', 'Sains Data', 'Jl. ke ujung dunia'],
    ['0023088703', 'Dr. Jenoya, S.T., M.Kom', 'Sistem Informasi', 'Jl. Seoul No. 21'],
    ['0015048201', 'Prof. Chenle, S.T., M.T', 'Teknik Informatika', 'Jl. Myongdong No.100']
];

try {
    if ($db_driver === 'mysql') {
        $pdo_init = new PDO("mysql:host=$mysql_host;port=$mysql_port", $mysql_user, $mysql_pass);
        $pdo_init->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$mysql_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        $pdo = new PDO("mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_db;charset=utf8mb4", $mysql_user, $mysql_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $pdo->exec("CREATE TABLE IF NOT EXISTS dosen (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nidn VARCHAR(30) UNIQUE NOT NULL,
            nama VARCHAR(150) NOT NULL,
            prodi VARCHAR(100) NOT NULL,
            alamat TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        $db_file = __DIR__ . '/database/database.sqlite';
        if (!file_exists(dirname($db_file))) {
            $db_file = __DIR__ . '/database_dosen.sqlite';
        }
        $pdo = new PDO("sqlite:" . $db_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $pdo->exec("CREATE TABLE IF NOT EXISTS dosen (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nidn TEXT UNIQUE NOT NULL,
            nama TEXT NOT NULL,
            prodi TEXT NOT NULL,
            alamat TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    $count = (int)$pdo->query("SELECT COUNT(*) FROM dosen")->fetchColumn();
    if ($count === 0) {
        $ins = $pdo->prepare("INSERT INTO dosen (nidn, nama, prodi, alamat) VALUES (?, ?, ?, ?)");
        foreach ($default_samples as $s) {
            $ins->execute($s);
        }
    }

} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

// ==========================================
// 2. LOGIKA CRUD & CONTROLLER
// ==========================================
$swal_icon = '';
$swal_msg  = '';

// FITUR RESET KE DATA AWAL
if (isset($_GET['aksi']) && $_GET['aksi'] === 'reset_default') {
    $pdo->exec("DELETE FROM dosen");
    $ins = $pdo->prepare("INSERT INTO dosen (nidn, nama, prodi, alamat) VALUES (?, ?, ?, ?)");
    foreach ($default_samples as $s) {
        $ins->execute($s);
    }
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?status=reset_success");
    exit;
}

// AKSI 1: TAMBAH DATA (CREATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $nidn   = trim($_POST['nidn'] ?? '');
    $nama   = trim($_POST['nama'] ?? '');
    $prodi  = trim($_POST['prodi'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($nidn === '' || $nama === '' || $prodi === '' || $alamat === '') {
        $swal_icon = 'warning';
        $swal_msg  = 'Seluruh kolom formulir wajib diisi dengan lengkap!';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO dosen (nidn, nama, prodi, alamat) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nidn, $nama, $prodi, $alamat]);
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?status=add_success");
            exit;
        } catch (PDOException $e) {
            $swal_icon = 'error';
            if (str_contains($e->getMessage(), 'UNIQUE') || str_contains($e->getMessage(), '1062')) {
                $swal_msg = "NIDN '$nidn' sudah terdaftar! Harap gunakan NIDN yang lain.";
            } else {
                $swal_msg = 'Gagal menyimpan: ' . $e->getMessage();
            }
        }
    }
}

// AKSI 2: EDIT DATA (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'edit') {
    $id     = (int)($_POST['id'] ?? 0);
    $nidn   = trim($_POST['nidn'] ?? '');
    $nama   = trim($_POST['nama'] ?? '');
    $prodi  = trim($_POST['prodi'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($id <= 0 || $nidn === '' || $nama === '' || $prodi === '' || $alamat === '') {
        $swal_icon = 'warning';
        $swal_msg  = 'Data untuk pembaruan tidak valid!';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE dosen SET nidn = ?, nama = ?, prodi = ?, alamat = ? WHERE id = ?");
            $stmt->execute([$nidn, $nama, $prodi, $alamat, $id]);
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?status=edit_success");
            exit;
        } catch (PDOException $e) {
            $swal_icon = 'error';
            if (str_contains($e->getMessage(), 'UNIQUE') || str_contains($e->getMessage(), '1062')) {
                $swal_msg = "NIDN '$nidn' sudah digunakan oleh dosen lain!";
            } else {
                $swal_msg = 'Gagal memperbarui: ' . $e->getMessage();
            }
        }
    }
}

// AKSI 3: HAPUS DATA (DELETE)
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM dosen WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?status=delete_success");
        exit;
    }
}

// Deteksi Notifikasi Redirect URL
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'add_success') {
        $swal_icon = 'success';
        $swal_msg  = 'Data dosen baru berhasil ditambahkan!';
    } elseif ($_GET['status'] === 'edit_success') {
        $swal_icon = 'success';
        $swal_msg  = 'Data dosen berhasil diperbarui!';
    } elseif ($_GET['status'] === 'delete_success') {
        $swal_icon = 'success';
        $swal_msg  = 'Data dosen berhasil dihapus dari sistem!';
    } elseif ($_GET['status'] === 'reset_success') {
        $swal_icon = 'info';
        $swal_msg  = 'Data dosen berhasil di-reset ke data bawaan!';
    }
}

// AMBIL SEMUA DATA DOSEN
$stmt = $pdo->query("SELECT * FROM dosen ORDER BY id ASC");
$daftar_dosen = $stmt->fetchAll();

$total_dosen = count($daftar_dosen);
$list_prodi = [
    'Rekayasa Perangkat Lunak',
    'Sains Data',
    'Sistem Informasi',
    'Teknik Informatika',
    'Teknologi Informasi',
    'Manajemen Informatika'
];

function getProdiBadgeClass($prodi) {
    return match (trim($prodi)) {
        'Rekayasa Perangkat Lunak' => 'badge-rpl',
        'Sains Data'              => 'badge-sains',
        'Sistem Informasi'        => 'badge-si',
        'Teknik Informatika'      => 'badge-ti',
        default                   => 'badge-default',
    };
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Data Dosen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --app-font: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --brand-primary: #3b82f6;
            --brand-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
        }
        body {
            background-color: #f8fafc;
            font-family: var(--app-font);
            color: #1e293b;
        }
        .navbar-brand-custom {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff !important;
        }
        .header-bg {
            background: var(--brand-gradient);
            padding: 35px 0 60px 0;
            color: white;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.15);
        }
        .stats-container {
            margin-top: -35px;
        }
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -5px rgba(37, 99, 235, 0.12);
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.5rem;
        }
        .card-main {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            background: #ffffff;
            overflow: hidden;
        }
        .table-custom thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding: 16px 20px;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }
        .table-custom tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .nidn-pill {
            background-color: #e2e8f0;
            color: #334155;
            font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .badge-prodi {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .badge-rpl { background-color: #dbeafe; color: #2563eb; border: 1px solid #bfdbfe; }
        .badge-sains { background-color: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .badge-si { background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
        .badge-ti { background-color: #ede9fe; color: #7c3aed; border: 1px solid #ddd6fe; }
        .badge-default { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        .btn-act {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            border: 1px solid transparent;
        }
        .btn-act-view { background-color: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
        .btn-act-edit { background-color: #fffbeb; color: #d97706; border-color: #fde68a; }
        .btn-act-del { background-color: #fef2f2; color: #dc2626; border-color: #fecaca; }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        .modal-header { padding: 20px 28px; }
        .modal-body { padding: 28px; }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            font-size: 0.92rem;
        }
    </style>
</head>
<body>

    <header class="header-bg">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-3 text-white">
                        <i class="bi bi-mortarboard-fill fs-3"></i>
                    </div>
                    <div>
                        <h4 class="navbar-brand-custom mb-0">PORTAL DATA DOSEN</h4>
                        <small class="text-white-50">Sistem Informasi Pengelolaan Data Dosen</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="resetToDefault()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Data
                    </button>
                    <button class="btn btn-light btn-sm rounded-pill px-3 shadow-sm fw-semibold" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak
                    </button>
                    <button class="btn btn-success btn-sm rounded-pill px-3 shadow-sm fw-semibold" onclick="exportToCSV()">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        <div class="row g-3 stats-container mb-4">
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Dosen Terdaftar</div>
                        <h3 class="fw-bold mb-0 text-dark"><?= $total_dosen ?> <span class="fs-6 fw-normal text-muted">Orang</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Program Studi Terdata</div>
                        <h3 class="fw-bold mb-0 text-dark"><?= count($list_prodi) ?> <span class="fs-6 fw-normal text-muted">Prodi</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Status Database</div>
                        <h5 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                            <span class="badge rounded-pill bg-success text-white px-2 py-1" style="font-size:0.7rem;">ONLINE</span>
                            Active
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-main">
            <div class="p-4 border-bottom bg-white">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-5 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="liveSearchInput" class="form-control bg-light border-start-0" 
                                   placeholder="Pencarian instan (NIDN, Nama, Alamat)..." onkeyup="filterTableLive()">
                            <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="clearSearch()">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <select id="prodiFilterSelect" class="form-select bg-light" onchange="filterTableLive()">
                            <option value="">-- Semua Program Studi --</option>
                            <?php foreach ($list_prodi as $lp): ?>
                                <option value="<?= htmlspecialchars($lp) ?>"><?= htmlspecialchars($lp) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12 text-lg-end">
                        <button type="button" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Dosen Baru
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 bg-light bg-opacity-50 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-table text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Daftar Dosen Terdaftar</h6>
                </div>
                <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill shadow-xs" id="badgeRowCounter">
                    Total Ditampilkan: <?= count($daftar_dosen) ?> Data
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-custom align-middle" id="dosenTable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">NO</th>
                            <th style="width: 150px;">NIDN</th>
                            <th>NAMA DOSEN</th>
                            <th>PROGRAM STUDI</th>
                            <th>ALAMAT</th>
                            <th class="text-center" style="width: 140px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="dosenTableBody">
                        <?php $no = 1; foreach ($daftar_dosen as $dosen): ?>
                            <tr class="dosen-row" 
                                data-id="<?= $dosen['id'] ?>"
                                data-nidn="<?= htmlspecialchars($dosen['nidn']) ?>"
                                data-nama="<?= htmlspecialchars($dosen['nama']) ?>"
                                data-prodi="<?= htmlspecialchars($dosen['prodi']) ?>"
                                data-alamat="<?= htmlspecialchars($dosen['alamat']) ?>">
                                <td class="text-center fw-bold text-secondary row-number"><?= $no++ ?></td>
                                <td>
                                    <span class="nidn-pill" onclick="copyNidn('<?= htmlspecialchars($dosen['nidn']) ?>')">
                                        <i class="bi bi-copy text-muted" style="font-size: 0.75rem;"></i>
                                        <?= htmlspecialchars($dosen['nidn']) ?>
                                    </span>
                                </td>
                                <td><div class="fw-bold text-dark fs-6"><?= htmlspecialchars($dosen['nama']) ?></div></td>
                                <td>
                                    <span class="badge-prodi <?= getProdiBadgeClass($dosen['prodi']) ?>">
                                        <i class="bi bi-check2-square"></i>
                                        <?= htmlspecialchars($dosen['prodi']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-secondary small d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        <span><?= htmlspecialchars($dosen['alamat']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <button class="btn-act btn-act-view" onclick="showDetail(this)"><i class="bi bi-eye-fill"></i></button>
                                        <button class="btn-act btn-act-edit" onclick="openEditModal(this)"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="btn-act btn-act-del" onclick="confirmDelete(<?= $dosen['id'] ?>, '<?= htmlspecialchars(addslashes($dosen['nama'])) ?>')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="noMatchMessage" class="text-center py-5 d-none text-muted">
                <i class="bi bi-search fs-1 d-block mb-2 text-secondary opacity-50"></i>
                <h6 class="fw-bold text-dark">Data Dosen Tidak Ditemukan</h6>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold mb-0">Tambah Data Dosen</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NIDN</label>
                            <input type="text" name="nidn" class="form-control" placeholder="Contoh: 0005078504" required pattern="[0-9]{5,20}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NAMA LENGKAP & GELAR</label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Keonho, S.T., M.Kom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">PROGRAM STUDI</label>
                            <select name="prodi" class="form-select" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php foreach ($list_prodi as $lp): ?>
                                    <option value="<?= htmlspecialchars($lp) ?>"><?= htmlspecialchars($lp) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">ALAMAT LENGKAP</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">Simpan Data Dosen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="aksi" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-bold mb-0">Perbarui Data Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NIDN</label>
                            <input type="text" name="nidn" id="edit_nidn" class="form-control" required pattern="[0-9]{5,20}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NAMA LENGKAP & GELAR</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">PROGRAM STUDI</label>
                            <select name="prodi" id="edit_prodi" class="form-select" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php foreach ($list_prodi as $lp): ?>
                                    <option value="<?= htmlspecialchars($lp) ?>"><?= htmlspecialchars($lp) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">ALAMAT LENGKAP</label>
                            <textarea name="alamat" id="edit_alamat" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4 fw-semibold">Perbarui Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-vcard me-2"></i>Kartu Profil Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center text-white fw-bold shadow" 
                         id="detailAvatar" 
                         style="width: 80px; height: 80px; border-radius: 50%; font-size: 2rem; background: var(--brand-gradient);">
                        KH
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="detailNama">Nama Dosen</h5>
                    <div class="card border-0 bg-light p-3 text-start rounded-3 mb-3 mt-3">
                        <div class="row g-2">
                            <div class="col-4 text-muted small fw-semibold">NIDN:</div>
                            <div class="col-8 fw-bold text-dark font-monospace" id="detailNidn">-</div>
                            <div class="col-4 text-muted small fw-semibold">Program Studi:</div>
                            <div class="col-8 fw-semibold" id="detailProdi">-</div>
                            <div class="col-4 text-muted small fw-semibold">Alamat:</div>
                            <div class="col-8 text-secondary small" id="detailAlamat">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
        const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));

        <?php if ($swal_msg !== ''): ?>
            Swal.fire({
                icon: '<?= $swal_icon ?>',
                title: '<?= $swal_icon === "success" ? "Berhasil!" : "Peringatan" ?>',
                text: '<?= htmlspecialchars($swal_msg) ?>',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        <?php endif; ?>

        function filterTableLive() {
            const query = document.getElementById('liveSearchInput').value.toLowerCase().trim();
            const prodi = document.getElementById('prodiFilterSelect').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.dosen-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const rowNidn   = (row.dataset.nidn || '').toLowerCase();
                const rowNama   = (row.dataset.nama || '').toLowerCase();
                const rowProdi  = (row.dataset.prodi || '').toLowerCase();
                const rowAlamat = (row.dataset.alamat || '').toLowerCase();

                const matchQuery = !query || rowNidn.includes(query) || rowNama.includes(query) || rowAlamat.includes(query);
                const matchProdi = !prodi || rowProdi === prodi;

                if (matchQuery && matchProdi) {
                    row.style.display = '';
                    visibleCount++;
                    row.querySelector('.row-number').innerText = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('badgeRowCounter').innerText = `Total Ditampilkan: ${visibleCount} Data`;
        }

        function clearSearch() {
            document.getElementById('liveSearchInput').value = '';
            document.getElementById('prodiFilterSelect').value = '';
            filterTableLive();
        }

        function copyNidn(nidn) {
            navigator.clipboard.writeText(nidn).then(() => {
                Swal.fire({ icon: 'success', title: 'NIDN Disalin!', timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
            });
        }

        function showDetail(btn) {
            const row = btn.closest('.dosen-row');
            document.getElementById('detailNama').innerText = row.dataset.nama;
            document.getElementById('detailNidn').innerText = row.dataset.nidn;
            document.getElementById('detailProdi').innerText = row.dataset.prodi;
            document.getElementById('detailAlamat').innerText = row.dataset.alamat;
            modalDetail.show();
        }

        function openEditModal(btn) {
            const row = btn.closest('.dosen-row');
            document.getElementById('edit_id').value     = row.dataset.id;
            document.getElementById('edit_nidn').value   = row.dataset.nidn;
            document.getElementById('edit_nama').value   = row.dataset.nama;
            document.getElementById('edit_prodi').value  = row.dataset.prodi;
            document.getElementById('edit_alamat').value = row.dataset.alamat;
            modalEdit.show();
        }

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Data Dosen?',
                html: `Apakah Anda yakin ingin menghapus data <strong>${nama}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?hapus=${id}`;
                }
            });
        }

        function resetToDefault() {
            Swal.fire({
                title: 'Reset ke Data Bawaan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Reset'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?aksi=reset_default';
                }
            });
        }

        function exportToCSV() {
            const rows = document.querySelectorAll('.dosen-row');
            let csvContent = "data:text/csv;charset=utf-8,NO,NIDN,NAMA DOSEN,PROGRAM STUDI,ALAMAT\n";
            let no = 1;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    csvContent += `${no++},"${row.dataset.nidn}","${row.dataset.nama}","${row.dataset.prodi}","${row.dataset.alamat}"\n`;
                }
            });
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "data_dosen.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
