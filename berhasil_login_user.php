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

// start from here to calculate k means
$result = mysqli_query($conn, "SELECT * FROM tb_obat");

$data=[];

while($row=mysqli_fetch_array($result)){
	$data[]=$row;
	$nama[]=$row['nama'];
}

//hitung Euclidean Distance Space
function jarakEuclidean($data = array(), $centroid = array())
{
	return sqrt(pow(($data[0] - $centroid[0]), 2) + pow(($data[1] - $centroid[1]), 2));
}

function jarakTerdekat($jarak_ke_centroid = array(), $centroid)
{
	foreach ($jarak_ke_centroid as $key => $value) {
		if (!isset($minimum)) {
			$minimum = $value;
			$cluster = ($key + 1);
			continue;
		} else if ($value < $minimum) {
			$minimum = $value;
			$cluster = ($key + 1);
		}
	}
	return array(
		'cluster' => $cluster,
		'value' => $minimum,
	);
}

function perbaruiCentroid($table_iterasi, &$hasil_cluster)
{
	$hasil_cluster = [];
	//looping untuk mengelompokan x dan y sesuai cluster
	foreach ($table_iterasi as $key => $value) {
		$hasil_cluster[($value['jarak_terdekat']['cluster'] - 1)][0][] = $value['data'][0]; //data x
		$hasil_cluster[($value['jarak_terdekat']['cluster'] - 1)][1][] = $value['data'][1]; //data y
	}
	$new_centroid = [];
	//looping untuk mencari nilai centroid baru dengan cara mencari rata2 dari masing2 data(0=x dan 1=y) 
	foreach ($hasil_cluster as $key => $value) {
		$new_centroid[$key] = [
			array_sum($value[0]) / count($value[0]),
			array_sum($value[1]) / count($value[1])
		];
	}
	//sorting berdasarkan cluster
	ksort($new_centroid);
	return $new_centroid;
}

function centroidBerubah($centroid, $iterasi)
{
	$centroid_lama = flatten_array($centroid[($iterasi - 1)]); //flattern array
	$centroid_baru = flatten_array($centroid[$iterasi]); //flatten array
	//hitbandingkan centroid yang lama dan baru jika berubah return true, jika tidak berubah/jumlah sama=0 return false
	$jumlah_sama = 0;
	for ($i = 0; $i < count($centroid_lama); $i++) {
		if ($centroid_lama[$i] === $centroid_baru[$i]) {
			$jumlah_sama++;
		}
	}
	return $jumlah_sama === count($centroid_lama) ? false : true;
}

function flatten_array($arg)
{
	return is_array($arg) ? array_reduce($arg, function ($c, $a) {
		return array_merge($c, flatten_array($a));
	}, []) : [$arg];
}

function pointingHasilCluster($hasil_cluster)
{
	$result = [];
	foreach ($hasil_cluster as $key => $value) {
		for ($i = 0; $i < count($value[0]); $i++) {
			$result[$key][] = [$hasil_cluster[$key][0][$i], $hasil_cluster[$key][1][$i]];
		}
	}
	return ksort($result);
}

$sql = "SELECT * FROM tb_obat";
$data = [];
$result = mysqli_query($conn, $sql);
//masukan data stock dan terjual ke array data
while ($row = mysqli_fetch_assoc($result)) {
	$data[] = [$row['stock'], $row['terjual']];
}

//jumlah cluster
$cluster = 2;
$variable_x = 'stock';
$variable_y = 'terjual';

$rand = [];
//centroid awal ambil random dari data
for ($i = 0; $i < $cluster; $i++) {
	$temp = rand(0, (count($data) - 1));
	while (in_array($rand, $data)) {
		$temp = rand(0, (count($data) - 1));
	}
	$rand[] = $temp;
	$centroid[0][] = [
		$data[$rand[$i]][0],
		$data[$rand[$i]][1]
	];
}

$hasil_iterasi = [];
$hasil_cluster = [];

//iterasi
$iterasi = 0;
while (true) {
	$table_iterasi = array();
	//untuk setiap data ke i (x dan y)
	foreach ($data as $key => $value) {
		//untuk setiap table centroid pada iterasi ke i
		$table_iterasi[$key]['data'] = $value;
		foreach ($centroid[$iterasi] as $key_c => $value_c) {
			//hitung jarak euclidean 
			$table_iterasi[$key]['jarak_ke_centroid'][] = jarakEuclidean($value, $value_c);
		}
		//hitung jarak terdekat dan tentukan cluster nya
		$table_iterasi[$key]['jarak_terdekat'] = jarakTerdekat($table_iterasi[$key]['jarak_ke_centroid'], $centroid);
	}
	array_push($hasil_iterasi, $table_iterasi);
	$centroid[++$iterasi] = perbaruiCentroid($table_iterasi, $hasil_cluster);
	$lanjutkan = centroidBerubah($centroid, $iterasi);
	$boolval = boolval($lanjutkan) ? 'ya' : 'tidak';
	// echo 'proses iterasi ke-'.$iterasi.' : lanjutkan iterasi ? '.$boolval.'<br>';
	if (!$lanjutkan)
		break;
	//loop sampai setiap nilai centroid sama dengan nilai centroid sebelumnya
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
							<a class="nav-link" aria-current="page" href="berhasil_login_user.php?page=beranda"><b>Beranda</b></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="berhasil_login_user.php?page=data_apotek_user"><b>Data Apotek</b></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="berhasil_login_user.php?page=kmeans"><b>Hasil Clusetring</b></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="berhasil_login_user.php?page=kontak_kami"><b>Kontak Kami</b></a>
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
				case 'data_apotek_user':
					include "halaman/data_apotek_user.php";
					break;
				case 'data_obat_user':
					include "halaman/data_obat_user.php";
					break;
				case 'kmeans':
					include "halaman/kmeans.php";
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