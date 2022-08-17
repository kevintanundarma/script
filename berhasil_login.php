<?php

include 'config.php';

session_start();

if (!isset($_SESSION['username'])) {
	header("Location: index.php");
}

// Ambil data dari tabel apotek
$data_apotek = array();
$sql = "SELECT * FROM tb_apotek";
$result = mysqli_query($conn, $sql);
while ($dataApotek =  mysqli_fetch_array($result)) {
	$data_apotek[] = $dataApotek;
}

// Ambil data obat berdasarkan id apotek
$id_apotek = 0;
if (isset($_GET['id_apotek'])) {
	$id_apotek = $_GET['id_apotek'];
}

// PAGINATION & SEARCH
$banyakDataPerHalaman = 25;
if (isset($_GET['halaman'])) {
	$halamanAktif = $_GET['halaman'];
} else {
	$halamanAktif = 1;
}

$dataAwal = ($halamanAktif > 1) ? ($halamanAktif * $banyakDataPerHalaman) - $banyakDataPerHalaman : 0;

$banyakData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_obat WHERE id_apotek = $id_apotek"));
$banyakHalaman = ceil($banyakData / $banyakDataPerHalaman);

if (isset($_POST['cari'])) {
	$keyword = trim($_POST['keyword']);
	$id_apotek = $_POST['id_apotek'];
	$sql = "SELECT * FROM tb_obat WHERE nama LIKE '%$keyword%' AND id_apotek =  $id_apotek  LIMIT $dataAwal, $banyakDataPerHalaman";
} else {
	$sql = "SELECT * FROM tb_obat WHERE id_apotek = $id_apotek LIMIT $dataAwal, $banyakDataPerHalaman";
}

$data_obat = array();
$result = mysqli_query($conn, $sql);
while ($dataObat =  mysqli_fetch_array($result)) {
	$data_obat[] = $dataObat;
}

// APOTEK //
// CREATE
function create_dataapotek($post)
{
	global $conn;

	$nama = strip_tags($post['nama']);
	$alamat = strip_tags($post['alamat']);

	$sql = "INSERT INTO tb_apotek VALUES(NULL, '$nama', '$alamat')";

	mysqli_query($conn, $sql,);
	return mysqli_affected_rows($conn);
}

if (isset($_POST['tambah'])) {
	if (create_dataapotek($_POST) > 0) {
		echo "<script>
				alert('Data berhasil ditambahkan');
				document.location.href = 'berhasil_login.php?page=data_apotek';
				</script>";
	} else {
		echo "<script>
				alert('Data gagal ditambahkan');
				document.location.href = 'berhasil_login.php?page=data_apotek';
				</script>";
	}
}

//EDIT
function ubah_data($post)
{
	global $conn;

	$id = strip_tags($post['id']);
	$nama = strip_tags($post['nama']);
	$alamat = strip_tags($post['alamat']);

	$sql = "UPDATE tb_apotek SET nama = '$nama', alamat = '$alamat' WHERE id = $id";

	mysqli_query($conn, $sql,);
	return mysqli_affected_rows($conn);
}
if (isset($_POST['ubah'])) {
	if (ubah_data($_POST) > 0) {
		echo "<script>
				alert('Data berhasil diubah');
				document.location.href = 'berhasil_login.php?page=data_apotek';
				</script>";
	} else {
		echo "<script>
				alert('Data gagal diubah');
				document.location.href = 'berhasil_login.php?page=data_apotek';
				</script>";
	}
}

//DELETE
function hapus_data($post)
{
	global $conn;

	$id = strip_tags($post['id']);

	$sql = "DELETE FROM tb_apotek WHERE id = $id";

	mysqli_query($conn, $sql,);
	return mysqli_affected_rows($conn);
}

if (isset($_POST['hapus'])) {
	if ((hapus_data($_POST)) > 0) {
		echo "<script>
					alert('Data berhasil dihapus');
					document.location.href = 'berhasil_login.php?page=data_apotek';
					</script>";
	} else {
		echo "<script>
				alert('Data gagal dihapus');
				document.location.href = 'berhasil_login.php?page=data_apotek';
				</script>";
	}
}

// OBAT //
//CREATE
function create_dataobat($post)
{
	global $conn;

	// $id = strip_tags($post['id']);
	$id_apotek = strip_tags($post['id_apotek']);
	$nama = strip_tags($post['nama']);
	$harga = strip_tags($post['harga']);
	$stock = strip_tags($post['stock']);
	$terjual = strip_tags($post['terjual']);
	$tersedia = strip_tags($post['tersedia']);
	$kategori = strip_tags($post['kategori']);

	$sql = "INSERT INTO tb_obat VALUES(NULL, '$id_apotek', '$nama', '$harga', '$stock', '$terjual', '$tersedia', '$kategori')";

	mysqli_query($conn, $sql,);
	return array($id_apotek, mysqli_affected_rows($conn));
}

if (isset($_POST['tambahobat'])) {
	$result = create_dataobat($_POST);
	if ($result[1] > 0) {
		echo "<script>
				alert('Data berhasil ditambahkan');
				document.location.href = 'berhasil_login.php?page=data_obat&id_apotek=$result[0]';
				</script>";
	} else {
		echo "<script>
				alert('Data gagal ditambahkan');
				document.location.href = 'berhasil_login.php?page=data_obat&id_apotek=$result[0]';
				</script>";
	}
}

//EDIT
function ubah_dataobat($post)
{
	global $conn;

	$id = strip_tags($post['id']);
	$id_apotek = strip_tags($post['id_apotek']);
	$nama = strip_tags($post['nama']);
	$harga = strip_tags($post['harga']);
	$stock = strip_tags($post['stock']);
	$terjual = strip_tags($post['terjual']);
	$tersedia = strip_tags($post['tersedia']);
	$kategori = strip_tags($post['kategori']);

	$sql = "UPDATE tb_obat SET nama = '$nama', harga = '$harga', stock = '$stock', terjual = '$terjual', tersedia = '$tersedia', kategori = '$kategori' WHERE id = $id AND id_apotek = $id_apotek";

	mysqli_query($conn, $sql,);
	return mysqli_affected_rows($conn);
}
if (isset($_POST['ubahobat'])) {
	if (ubah_dataobat($_POST) == 1) {
		echo "<script>
				alert('Data berhasil diubah');
				document.location.href = 'berhasil_login.php?page=data_obat&id_apotek=$id_apotek';
				</script>";
	} else {
		echo "<script>
				alert('Data gagal diubah');
				document.location.href = 'berhasil_login.php?page=data_obat&id_apotek=$id_apotek';
				</script>";
	}
}

//DELETE
function hapus_dataobat($post)
{
	global $conn;

	$id = strip_tags($post['id']);
	$id_apotek = strip_tags($post['id_apotek']);

	$sql = "DELETE FROM tb_obat WHERE id = $id";

	mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn);
}

if (isset($_POST['hapusobat'])) {
	if ((hapus_dataobat($_POST)) > 0) {
		echo "<script>
				alert('Data berhasil dihapus');
				document.location.href = 'berhasil_login.php?page=data_obat&id_apotek=$id_apotek';
				</script>";
	} else {
		echo "<script>
				alert('Data gagal dihapus');
				document.location.href = 'berhasil_login.php?page=data_obat&id_apotek=$id_apotek';
				</script>";
	}
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="icon" type="image/x-icon" href="images/logo.jpg">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<title>Sistem Informasi Persediaan Obat</title>
</head>

<body>
	<div class="content">
		<nav class="navbar navbar-expand-lg bg-secondary">
			<div class="container-fluid">
				<a class="navbar-brand" href="#"><b>S. I. P. O.</b></a>
				<button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarCollapse">
					<ul class="navbar-nav ">
						<li class="nav-item">
							<a class="nav-link" aria-current="page" href="berhasil_login.php?page=beranda"><b>Beranda</b></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="berhasil_login.php?page=data_apotek"><b>Data Apotek</b></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href=""><b>Hasil Clusetring</b></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="berhasil_login.php?page=kontak_kami"><b>Kontak Kami</b></a>
						</li>
					</ul>
				</div>
				<a href="logout.php" class="btn btn-outline-info" role="button" aria-disabled="true"><b>Logout</b></a>
			</div>
		</nav>
	</div>

	<div class="container">
		<?php
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			switch ($page) {
				case 'beranda':
					include "halaman/beranda.php";
					break;
				case 'data_apotek':
					include "halaman/data_apotek.php";
					break;
				case 'data_obat':
					include "halaman/data_obat.php";
					break;
				case 'kontak_kami':
					include "halaman/kontak_kami.php";
					break;
			}
		} else {
			include "halaman/beranda.php";
		}
		?>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>