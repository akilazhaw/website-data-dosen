<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Data Dosen - Laravel</title>
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
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
        /* NAVBAR */
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
        /* STAT CARD OVERLAY */
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

        /* MAIN CARD & TABLE */
        .card-main {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            background: #ffffff;
            overflow: hidden;
        }
        .table-custom {
            margin-bottom: 0;
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
        .table-custom tbody tr {
            transition: background-color 0.2s ease;
        }
        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }
        .table-custom tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* NIDN PILL */
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
        .nidn-pill:hover {
            background-color: #cbd5e1;
            color: #0f172a;
        }

        /* PROGRAM STUDI BADGES */
        .badge-prodi {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .badge-rpl {
            background-color: #dbeafe;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }
        .badge-sains {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        .badge-si {
            background-color: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }
        .badge-ti {
            background-color: #ede9fe;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }
        .badge-default {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* TOMBOL AKSI */
        .btn-act {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 0.95rem;
            border: 1px solid transparent;
        }
        .btn-act-view {
            background-color: #f0fdf4;
            color: #16a34a;
            border-color: #bbf7d0;
        }
        .btn-act-view:hover {
            background-color: #16a34a;
            color: #ffffff;
        }
        .btn-act-edit {
            background-color: #fffbeb;
            color: #d97706;
            border-color: #fde68a;
        }
        .btn-act-edit:hover {
            background-color: #d97706;
            color: #ffffff;
        }
        .btn-act-del {
            background-color: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }
        .btn-act-del:hover {
            background-color: #dc2626;
            color: #ffffff;
        }

        /* MODAL STYLING */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        .modal-header {
            padding: 20px 28px;
        }
        .modal-body {
            padding: 28px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            font-size: 0.92rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        @media print {
            .no-print, .stats-container, .header-bg, .btn, .nidn-pill i {
                display: none !important;
            }
            body {
                background: white;
            }
            .card-main {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER / HERO SECTION -->
    <header class="header-bg no-print">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-3 text-white">
                        <i class="bi bi-mortarboard-fill fs-3"></i>
                    </div>
                    <div>
                        <h4 class="navbar-brand-custom mb-0">PORTAL DATA DOSEN</h4>
                        <small class="text-white-50">Laravel Web Application - Pemrograman Berbasis Website Lanjutan</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form id="formResetDefault" action="{{ route('dosen.reset') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button class="btn btn-outline-light btn-sm rounded-pill px-3" onclick="resetToDefault()" title="Reset ke data awal">
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

        <!-- 3 KARTU STATISTIK -->
        <div class="row g-3 stats-container mb-4 no-print">
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Total Dosen Terdaftar</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $total_dosen }} <span class="fs-6 fw-normal text-muted">Orang</span></h3>
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
                        <h3 class="fw-bold mb-0 text-dark">{{ count($list_prodi) }} <span class="fs-6 fw-normal text-muted">Prodi</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-medium">Framework & Engine</div>
                        <h5 class="fw-bold mb-0 text-success d-flex align-items-center gap-2">
                            <span class="badge rounded-pill bg-success text-white px-2 py-1" style="font-size:0.7rem;">ONLINE</span>
                            Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CARD -->
        <div class="card-main">
            <!-- TOOLBAR: SEARCH, FILTER & TAMBAH DOSEN -->
            <div class="p-4 border-bottom bg-white no-print">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-5 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="liveSearchInput" class="form-control bg-light border-start-0" 
                                   placeholder="Pencarian instan (NIDN, Nama, Alamat)..." onkeyup="filterTableLive()">
                            <button class="btn btn-light border border-start-0 text-muted" type="button" onclick="clearSearch()" title="Bersihkan">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <select id="prodiFilterSelect" class="form-select bg-light" onchange="filterTableLive()">
                            <option value="">-- Semua Program Studi --</option>
                            @foreach ($list_prodi as $lp)
                                <option value="{{ $lp }}">{{ $lp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12 text-lg-end">
                        <button type="button" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Dosen Baru
                        </button>
                    </div>
                </div>
            </div>

            <!-- HEADER TABEL -->
            <div class="px-4 py-3 bg-light bg-opacity-50 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-table text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-dark">Daftar Dosen Terdaftar</h6>
                </div>
                <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill shadow-xs" id="badgeRowCounter">
                    Total Ditampilkan: {{ count($daftar_dosen) }} Data
                </span>
            </div>

            <!-- TABEL DATA DOSEN -->
            <div class="table-responsive">
                <table class="table table-custom align-middle" id="dosenTable">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">NO</th>
                            <th style="width: 150px;">NIDN</th>
                            <th>NAMA DOSEN</th>
                            <th>PROGRAM STUDI</th>
                            <th>ALAMAT</th>
                            <th class="text-center no-print" style="width: 140px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="dosenTableBody">
                        @forelse ($daftar_dosen as $dosen)
                            @php
                                $badgeClass = match (trim($dosen->prodi)) {
                                    'Rekayasa Perangkat Lunak' => 'badge-rpl',
                                    'Sains Data'               => 'badge-sains',
                                    'Sistem Informasi'         => 'badge-si',
                                    'Teknik Informatika'       => 'badge-ti',
                                    default                    => 'badge-default',
                                };
                            @endphp
                            <tr class="dosen-row" 
                                data-id="{{ $dosen->id }}"
                                data-nidn="{{ $dosen->nidn }}"
                                data-nama="{{ $dosen->nama }}"
                                data-prodi="{{ $dosen->prodi }}"
                                data-alamat="{{ $dosen->alamat }}">
                                
                                <!-- NO -->
                                <td class="text-center fw-bold text-secondary row-number">{{ $loop->iteration }}</td>
                                
                                <!-- NIDN -->
                                <td>
                                    <span class="nidn-pill" onclick="copyNidn('{{ $dosen->nidn }}')" title="Klik untuk menyalin NIDN">
                                        <i class="bi bi-copy text-muted" style="font-size: 0.75rem;"></i>
                                        {{ $dosen->nidn }}
                                    </span>
                                </td>
                                
                                <!-- NAMA DOSEN -->
                                <td>
                                    <div class="fw-bold text-dark fs-6">{{ $dosen->nama }}</div>
                                </td>
                                
                                <!-- PROGRAM STUDI -->
                                <td>
                                    <span class="badge-prodi {{ $badgeClass }}">
                                        <i class="bi bi-check2-square"></i>
                                        {{ $dosen->prodi }}
                                    </span>
                                </td>
                                
                                <!-- ALAMAT -->
                                <td>
                                    <div class="text-secondary small d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        <span>{{ $dosen->alamat }}</span>
                                    </div>
                                </td>
                                
                                <!-- TOMBOL AKSI -->
                                <td class="text-center no-print">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Detail -->
                                        <button class="btn-act btn-act-view" onclick="showDetail(this)" title="Lihat Profil Lengkap">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <!-- Edit -->
                                        <button class="btn-act btn-act-edit" onclick="openEditModal(this)" title="Ubah Data">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <!-- Hapus -->
                                        <button class="btn-act btn-act-del" onclick="confirmDelete({{ $dosen->id }}, '{{ addslashes($dosen->nama) }}')" title="Hapus Data">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="noDataRow">
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    Belum ada data dosen yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ROW JIKA FILTER TIDAK DITEMUKAN -->
            <div id="noMatchMessage" class="text-center py-5 d-none text-muted">
                <i class="bi bi-search fs-1 d-block mb-2 text-secondary opacity-50"></i>
                <h6 class="fw-bold text-dark">Data Dosen Tidak Ditemukan</h6>
                <p class="small mb-2">Tidak ada data dosen yang sesuai dengan kata kunci pencarian Anda.</p>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="clearSearch()">
                    Reset Pencarian
                </button>
            </div>

        </div>

    </div>

    <!-- HIDDEN FORM UNTUK DELETE (LARAVEL RESOURCE) -->
    <form id="formDeleteDosen" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ========================================== -->
    <!-- MODAL 1: TAMBAH DATA DOSEN                 -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('dosen.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-plus-fill fs-4"></i>
                            <h5 class="modal-title fw-bold mb-0">Tambah Data Dosen</h5>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NIDN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-card-text text-muted"></i></span>
                                <input type="text" name="nidn" class="form-control" placeholder="Contoh: 0005078504" required pattern="[0-9]{5,20}" id="addNidnInput" oninput="validateNidn(this)">
                            </div>
                            <div class="form-text small" id="nidnHelper">Masukkan 10 digit nomor induk dosen nasional.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NAMA LENGKAP & GELAR <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person-badge text-muted"></i></span>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Keonho, S.T., M.Kom" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">PROGRAM STUDI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-mortarboard text-muted"></i></span>
                                <select name="prodi" class="form-select" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($list_prodi as $lp)
                                        <option value="{{ $lp }}">{{ $lp }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">ALAMAT LENGKAP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-geo-alt text-muted"></i></span>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Contoh: Jl. jalan kita berdua" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-secondary px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Data Dosen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 2: EDIT DATA DOSEN                   -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditDosen" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-dark">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-pencil-square fs-4"></i>
                            <h5 class="modal-title fw-bold mb-0">Perbarui Data Dosen</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NIDN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-card-text text-muted"></i></span>
                                <input type="text" name="nidn" id="edit_nidn" class="form-control" required pattern="[0-9]{5,20}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">NAMA LENGKAP & GELAR <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person-badge text-muted"></i></span>
                                <input type="text" name="nama" id="edit_nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">PROGRAM STUDI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-mortarboard text-muted"></i></span>
                                <select name="prodi" id="edit_prodi" class="form-select" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($list_prodi as $lp)
                                        <option value="{{ $lp }}">{{ $lp }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">ALAMAT LENGKAP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-geo-alt text-muted"></i></span>
                                <textarea name="alamat" id="edit_alamat" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-secondary px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4 rounded-3 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Perbarui Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 3: LIHAT DETAIL DOSEN (ID CARD)      -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-vcard me-2"></i>Kartu Profil Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <!-- AVATAR INISIAL -->
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center text-white fw-bold shadow" 
                         id="detailAvatar" 
                         style="width: 80px; height: 80px; border-radius: 50%; font-size: 2rem; background: var(--brand-gradient);">
                        KH
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="detailNama">Nama Dosen</h5>
                    <div class="mb-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                            <i class="bi bi-patch-check-fill me-1"></i> Dosen Aktif
                        </span>
                    </div>

                    <div class="card border-0 bg-light p-3 text-start rounded-3 mb-3">
                        <div class="row g-2">
                            <div class="col-4 text-muted small fw-semibold">NIDN:</div>
                            <div class="col-8 fw-bold text-dark font-monospace" id="detailNidn">-</div>

                            <div class="col-4 text-muted small fw-semibold">Program Studi:</div>
                            <div class="col-8 fw-semibold" id="detailProdi">-</div>

                            <div class="col-4 text-muted small fw-semibold">Alamat:</div>
                            <div class="col-8 text-secondary small" id="detailAlamat">-</div>
                        </div>
                    </div>

                    <button class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="copyDetailNidn()">
                        <i class="bi bi-copy me-1"></i> Salin NIDN Dosen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
        const modalDetail = new bootstrap.Modal(document.getElementById('modalDetail'));
        let currentDetailNidn = '';

        // NOTIFIKASI SWEETALERT FLASH DARI LARAVEL SESSION
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: "{{ session('info') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: "{!! implode('<br>', $errors->all()) !!}",
                confirmButtonColor: '#dc2626'
            });
        @endif

        // LIVE INSTANT CLIENT-SIDE FILTER & SEARCH
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

            const noMatchMsg = document.getElementById('noMatchMessage');
            if (visibleCount === 0 && rows.length > 0) {
                noMatchMsg.classList.remove('d-none');
            } else {
                noMatchMsg.classList.add('d-none');
            }
        }

        function clearSearch() {
            document.getElementById('liveSearchInput').value = '';
            document.getElementById('prodiFilterSelect').value = '';
            filterTableLive();
        }

        // SALIN NIDN
        function copyNidn(nidn) {
            navigator.clipboard.writeText(nidn).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'NIDN Disalin!',
                    text: `NIDN ${nidn} berhasil disalin ke clipboard.`,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        }

        function copyDetailNidn() {
            if (currentDetailNidn) {
                copyNidn(currentDetailNidn);
            }
        }

        // DETAIL PROFIL DOSEN
        function showDetail(btn) {
            const row = btn.closest('.dosen-row');
            const nidn = row.dataset.nidn;
            const nama = row.dataset.nama;
            const prodi = row.dataset.prodi;
            const alamat = row.dataset.alamat;

            currentDetailNidn = nidn;
            document.getElementById('detailNama').innerText = nama;
            document.getElementById('detailNidn').innerText = nidn;
            document.getElementById('detailProdi').innerText = prodi;
            document.getElementById('detailAlamat').innerText = alamat;

            const parts = nama.split(' ');
            let initials = parts[0] ? parts[0][0] : 'D';
            if (parts.length > 1 && parts[1]) {
                initials += parts[1][0];
            }
            document.getElementById('detailAvatar').innerText = initials.toUpperCase();

            modalDetail.show();
        }

        // BUKA MODAL EDIT (SET FORM ACTION KE ROUTE UPDATE)
        function openEditModal(btn) {
            const row = btn.closest('.dosen-row');
            const id = row.dataset.id;
            
            document.getElementById('formEditDosen').action = `{{ url('dosen') }}/${id}`;
            document.getElementById('edit_nidn').value   = row.dataset.nidn;
            document.getElementById('edit_nama').value   = row.dataset.nama;
            document.getElementById('edit_prodi').value  = row.dataset.prodi;
            document.getElementById('edit_alamat').value = row.dataset.alamat;
            modalEdit.show();
        }

        // KONFIRMASI HAPUS MODERN (SWEETALERT2 -> SUBMIT FORM DELETE)
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Data Dosen?',
                html: `Apakah Anda yakin ingin menghapus data <strong>${nama}</strong>?<br><small class='text-muted'>Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash-fill me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const formDelete = document.getElementById('formDeleteDosen');
                    formDelete.action = `{{ url('dosen') }}/${id}`;
                    formDelete.submit();
                }
            });
        }

        // RESET KE DATA AWAL
        function resetToDefault() {
            Swal.fire({
                title: 'Reset ke Data Bawaan?',
                text: 'Semua perubahan data akan dikembalikan ke data dosen standar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Reset',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formResetDefault').submit();
                }
            });
        }

        // EXPORT KE CSV
        function exportToCSV() {
            const rows = document.querySelectorAll('.dosen-row');
            let csvContent = "data:text/csv;charset=utf-8,NO,NIDN,NAMA DOSEN,PROGRAM STUDI,ALAMAT\n";

            let no = 1;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const nidn   = `"${row.dataset.nidn}"`;
                    const nama   = `"${row.dataset.nama.replace(/"/g, '""')}"`;
                    const prodi  = `"${row.dataset.prodi.replace(/"/g, '""')}"`;
                    const alamat = `"${row.dataset.alamat.replace(/"/g, '""')}"`;
                    csvContent += `${no++},${nidn},${nama},${prodi},${alamat}\n`;
                }
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `data_dosen_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                icon: 'success',
                title: 'Ekspor Berhasil!',
                text: 'File CSV berhasil diunduh ke komputer Anda.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        // VALIDASI INPUT NIDN
        function validateNidn(input) {
            const helper = document.getElementById('nidnHelper');
            const val = input.value.trim();
            if (val.length === 10) {
                helper.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Format NIDN tepat 10 digit.</span>';
            } else {
                helper.innerHTML = `<span class="text-muted">Terisi ${val.length} digit (standar NIDN adalah 10 digit).</span>`;
            }
        }
    </script>
</body>
</html>
