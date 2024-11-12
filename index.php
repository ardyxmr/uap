<?php
session_start();
include 'config.php';

// Tentukan halaman yang sedang aktif (Data Dosen atau Data Matakuliah)
$page = isset($_GET['page']) ? $_GET['page'] : 'dosen';

// Tambah Data untuk Dosen atau Matakuliah
if (isset($_POST['submit'])) {
    if ($page == 'dosen') {
        // Tambah data Dosen
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];
        $email = $_POST['email'];
        $matakuliah_id = $_POST['matakuliah_id'];
        $tahun_ajaran = $_POST['tahun_ajaran'];
        $sql = "INSERT INTO dosen (nama, nim, email, matakuliah_id, tahun_ajaran) VALUES ('$nama', '$nim', '$email', '$matakuliah_id', '$tahun_ajaran')";
        $conn->query($sql);
    } else if ($page == 'matakuliah') {
        // Tambah data Matakuliah
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        $sks = $_POST['sks'];
        $semester = $_POST['semester'];
        $tahun_mulai = $_POST['tahun_mulai'];
        $tahun_akhir = $_POST['tahun_akhir'];
        $tahun_ajaran = $tahun_mulai . '/' . $tahun_akhir;
        $sql = "INSERT INTO matakuliah (kode, nama, sks, semester, tahun_ajaran) VALUES ('$kode', '$nama', '$sks', '$semester', '$tahun_ajaran')";
        $conn->query($sql);
    }
}

// Edit Data untuk Dosen atau Matakuliah
if (isset($_POST['update'])) {
    if ($page == 'dosen') {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];
        $email = $_POST['email'];
        $matakuliah_id = $_POST['matakuliah_id'];
        $tahun_ajaran = $_POST['tahun_ajaran'];
        $sql = "UPDATE dosen SET nama='$nama', nim='$nim', email='$email', matakuliah_id='$matakuliah_id', tahun_ajaran='$tahun_ajaran' WHERE id='$id'";
        $conn->query($sql);
    } else if ($page == 'matakuliah') {
        $id = $_POST['id'];
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        $sks = $_POST['sks'];
        $semester = $_POST['semester'];
        $tahun_mulai = $_POST['tahun_mulai'];
        $tahun_akhir = $_POST['tahun_akhir'];
        $tahun_ajaran = $tahun_mulai . '/' . $tahun_akhir;
        $sql = "UPDATE matakuliah SET kode='$kode', nama='$nama', sks='$sks', semester='$semester', tahun_ajaran='$tahun_ajaran' WHERE id='$id'";
        
        $conn->query($sql);
    }
}

// Hapus Data untuk Dosen atau Matakuliah
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($page == 'dosen') {
        $sql = "DELETE FROM dosen WHERE id='$id'";
    } else if ($page == 'matakuliah') {
        $sql = "DELETE FROM matakuliah WHERE id='$id'";
    }
    $conn->query($sql);
}

// Mendapatkan Data untuk Edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    if ($page == 'dosen') {
        $result = $conn->query("SELECT * FROM dosen WHERE id='$id'");
        $row = $result->fetch_assoc();
    } else if ($page == 'matakuliah') {
        $result = $conn->query("SELECT * FROM matakuliah WHERE id='$id'");
        $row = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Dosen dan Matakuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Sistem Akademik</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if($page == 'dosen') echo 'active'; ?>" href="index.php?page=dosen">Data Dosen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if($page == 'matakuliah') echo 'active'; ?>" href="index.php?page=matakuliah">Data Matakuliah</a>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center"><?php echo ($page == 'dosen') ? 'Data Dosen' : 'Data Matakuliah'; ?></h2>

    <!-- Form Tambah/Edit Data -->
    <div class="card mb-4">
        <div class="card-header">
            <?php echo isset($row) ? 'Edit Data' : 'Tambah Data'; ?>
        </div>
        <div class="card-body">
            <form method="post" action="index.php?page=<?php echo $page; ?>">
                <input type="hidden" name="id" value="<?php echo isset($row) ? $row['id'] : ''; ?>">

                <?php if ($page == 'dosen') { ?>
                    <!-- Form Data Dosen -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required value="<?php echo isset($row) ? $row['nama'] : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" required value="<?php echo isset($row) ? $row['nim'] : ''; ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($row) ? $row['email'] : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="matakuliah_id" class="form-label">Matakuliah</label>
                            <select class="form-select" id="matakuliah_id" name="matakuliah_id" required onchange="updateTahunAjaran()">
                                <option value="">Pilih Matakuliah</option>
                                <?php
                                $matakuliah_result = $conn->query("SELECT id, nama, tahun_ajaran FROM matakuliah");
                                while ($matakuliah = $matakuliah_result->fetch_assoc()) {
                                    $selected = isset($row) && $row['matakuliah_id'] == $matakuliah['id'] ? 'selected' : '';
                                    echo "<option value='{$matakuliah['id']}' data-tahun='{$matakuliah['tahun_ajaran']}' $selected>{$matakuliah['nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" readonly value="<?php echo isset($row) ? $row['tahun_ajaran'] : ''; ?>">
                    </div>
                <?php } else { ?>
                    <!-- Form Data Matakuliah -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kode" class="form-label">Kode Matakuliah</label>
                            <input type="text" class="form-control" id="kode" name="kode" required value="<?php echo isset($row) ? $row['kode'] : ''; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Matakuliah</label>
                            <input type="text" class="form-control" id="nama" name="nama" required value="<?php echo isset($row) ? $row['nama'] : ''; ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sks" class="form-label">SKS</label>
                            <select id="sks" name="sks" class="form-select" required>
                                <option value="" disabled <?php echo !isset($row) ? 'selected' : ''; ?>>Pilih SKS</option>
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($row) && $row['sks'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="semester" class="form-label">Semester</label>
                            <select id="semester" name="semester" class="form-select" required>
                                <option value="" disabled <?php echo !isset($row) ? 'selected' : ''; ?>>Pilih Semester</option>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($row) && $row['semester'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                        <div class="d-flex">
                            <select class="form-select me-2" id="tahun_mulai" name="tahun_mulai" required>
                                <option value="" disabled <?php echo !isset($row) ? 'selected' : ''; ?>>Tahun Mulai</option>
                                <?php
                                $current_year = date("Y");
                                for ($year = $current_year; $year >= $current_year - 10; $year--) {
                                    echo "<option value='$year' " . (isset($row) && substr($row['tahun_ajaran'], 0, 4) == $year ? 'selected' : '') . ">$year</option>";
                                }
                                ?>
                            </select>
                            <span class="align-self-center">/</span>
                            <select class="form-select ms-2" id="tahun_akhir" name="tahun_akhir" required>
                                <option value="" disabled <?php echo !isset($row) ? 'selected' : ''; ?>>Tahun Akhir</option>
                                <?php
                                for ($year = $current_year + 1; $year >= $current_year - 9; $year--) {
                                    echo "<option value='$year' " . (isset($row) && substr($row['tahun_ajaran'], 5, 4) == $year ? 'selected' : '') . ">$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>

                <div class="text-center">
                    <button type="submit" name="<?php echo isset($row) ? 'update' : 'submit'; ?>" class="btn btn-primary">
                        <?php echo isset($row) ? 'Update' : 'Tambah'; ?>
                    </button>
                    <?php if (isset($row)) { ?>
                        <a href="index.php?page=<?php echo $page; ?>" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <?php if ($page == 'dosen') { ?>
                    <th style="width: 20%;">Nama</th>
                    <th style="width: 10%;">NIM</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 15%;">Matakuliah</th>
                    <th style="width: 10%;">Semester</th>
                    <th style="width: 10%;">Tahun Ajaran</th>
                <?php } else { ?>
                    <th style="width: 10%;">Kode</th>
                    <th style="width: 20%;">Nama Matakuliah</th>
                    <th style="width: 10%;">SKS</th>
                    <th style="width: 10%;">Semester</th>
                    <th style="width: 10%;">Tahun Ajaran</th>
                <?php } ?>
                <th style="width: 10%;">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($page == 'dosen') {
                $result = $conn->query("
                    SELECT dosen.id, dosen.nama, dosen.nim, dosen.email, 
                           matakuliah.nama AS matakuliah_nama, 
                           matakuliah.semester, matakuliah.tahun_ajaran
                    FROM dosen
                    LEFT JOIN matakuliah ON dosen.matakuliah_id = matakuliah.id
                ");

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['nim'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['matakuliah_nama'] . "</td>";
                    echo "<td>" . $row['semester'] . "</td>";
                    echo "<td>" . $row['tahun_ajaran'] . "</td>";
                    echo "<td>
                            <a href='index.php?page=dosen&edit=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='index.php?page=dosen&delete=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                $result = $conn->query("SELECT * FROM matakuliah");

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['kode'] . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['sks'] . "</td>";
                    echo "<td>" . $row['semester'] . "</td>";
                    echo "<td>" . $row['tahun_ajaran'] . "</td>";
                    echo "<td>
                            <a href='index.php?page=matakuliah&edit=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>
                            <a href='index.php?page=matakuliah&delete=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\");'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
function updateTahunAjaran() {
    var select = document.getElementById("matakuliah_id");
    var tahunAjaranInput = document.getElementById("tahun_ajaran");
    var selectedOption = select.options[select.selectedIndex];
    var tahunAjaran = selectedOption.getAttribute("data-tahun");
    
    tahunAjaranInput.value = tahunAjaran;
}
</script>
</body>
</html>
