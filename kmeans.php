<!doctype html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		a[aria-expanded="true"]{
			background-color: green;
		}
	</style>
</head>
<body>
<div class="container">
	<?php
	$link  = mysqli_connect("localhost", "root", "", "db_apotek");
	$query = $link->query("SELECT * FROM tb_obat");
	
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$data=[];

	while($row=$query->fetch_assoc()){
		$data[]=$row;
		$nama[]=$row['nama'];
	}
	//hitung Euclidean Distance Space
	function jarakEuclidean($data=array(),$centroid=array()){
		return sqrt(pow(($data[0]-$centroid[0]),2) + pow(($data[1]-$centroid[1]),2));
	}

	function jarakTerdekat($jarak_ke_centroid=array(),$centroid){
		foreach ($jarak_ke_centroid as $key => $value) {
			if(!isset($minimum)){
				$minimum=$value;
				$cluster=($key+1);
				continue;
			}
			else if($value<$minimum){
				$minimum=$value;
				$cluster=($key+1);
			}
		}
		return array(
			'cluster'=>$cluster,
			'value'=>$minimum,
		);
	}

	function perbaruiCentroid($table_iterasi,&$hasil_cluster){
		$hasil_cluster=[];
		//looping untuk mengelompokan x dan y sesuai cluster
		foreach ($table_iterasi as $key => $value) {
			$hasil_cluster[($value['jarak_terdekat']['cluster']-1)][0][]= $value['data'][0];//data x
			$hasil_cluster[($value['jarak_terdekat']['cluster']-1)][1][]= $value['data'][1];//data y
		}
		$new_centroid=[];
		//looping untuk mencari nilai centroid baru dengan cara mencari rata2 dari masing2 data(0=x dan 1=y) 
		foreach ($hasil_cluster as $key => $value) {
			$new_centroid[$key]= [
				array_sum($value[0])/count($value[0]),
				array_sum($value[1])/count($value[1])
			]; 
		}
		//sorting berdasarkan cluster
		ksort($new_centroid);
		return $new_centroid;
	}

	function centroidBerubah($centroid,$iterasi){
		$centroid_lama = flatten_array($centroid[($iterasi-1)]); //flattern array
		$centroid_baru = flatten_array($centroid[$iterasi]); //flatten array
		//hitbandingkan centroid yang lama dan baru jika berubah return true, jika tidak berubah/jumlah sama=0 return false
		$jumlah_sama=0;
		for($i=0;$i<count($centroid_lama);$i++){
			if($centroid_lama[$i]===$centroid_baru[$i]){
				$jumlah_sama++;
			}
		}
		return $jumlah_sama===count($centroid_lama) ? false : true; 
	}

	function flatten_array($arg) {
	  return is_array($arg) ? array_reduce($arg, function ($c, $a) { return array_merge($c, flatten_array($a)); },[]) : [$arg];
	}

	function pointingHasilCluster($hasil_cluster){
		$result=[];
		foreach ($hasil_cluster as $key => $value) {
			for ($i=0; $i<count($value[0]);$i++) { 
				$result[$key][]=[$hasil_cluster[$key][0][$i],$hasil_cluster[$key][1][$i]];
			}
		}
		return ksort($result);
	}

	//start program
	// get data dari database
	$link  = mysqli_connect("localhost", "root", "", "db_apotek");
	// cek koneksi
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit;
	}
	$query = $link->query("SELECT * FROM tb_obat");
	$data=[];
	//masukan data jumlah guru dan siswa ke array data
	while($row=$query->fetch_assoc()){
		$data[]=[$row['stock'],$row['terjual']];
	}
	
	//jumlah cluster
	$cluster = 2;
	$variable_x = 'stock';
	$variable_y = 'terjual';

	$rand=[];
	//centroid awal ambil random dari data
	for($i=0;$i<$cluster;$i++){
		$temp=rand(0,(count($data)-1));
		while(in_array($rand, $data)){
			$temp=rand(0,(count($data)-1));
		}
		$rand[]=$temp;
		$centroid[0][]=[
			$data[$rand[$i]][0],
			$data[$rand[$i]][1]
		];
	}
	
	$hasil_iterasi=[];
	$hasil_cluster=[];

	//iterasi
	$iterasi=0;
	while(true){
		$table_iterasi=array();
		//untuk setiap data ke i (x dan y)
		foreach ($data as $key => $value) {
			//untuk setiap table centroid pada iterasi ke i
			$table_iterasi[$key]['data']=$value;
			foreach ($centroid[$iterasi] as $key_c => $value_c) {
				//hitung jarak euclidean 
				$table_iterasi[$key]['jarak_ke_centroid'][]=jarakEuclidean($value,$value_c);	
			}
			//hitung jarak terdekat dan tentukan cluster nya
			$table_iterasi[$key]['jarak_terdekat']=jarakTerdekat($table_iterasi[$key]['jarak_ke_centroid'],$centroid);
		}
		array_push($hasil_iterasi, $table_iterasi);
		$centroid[++$iterasi]=perbaruiCentroid($table_iterasi,$hasil_cluster);
		$lanjutkan=centroidBerubah($centroid,$iterasi);
		$boolval = boolval($lanjutkan) ? 'ya' : 'tidak';
		// echo 'proses iterasi ke-'.$iterasi.' : lanjutkan iterasi ? '.$boolval.'<br>';
		if(!$lanjutkan)
			break;
		//loop sampai setiap nilai centroid sama dengan nilai centroid sebelumnya
	} 
	?>
	<div class="row justify-content-md-center">
		<h1>Klasterisasi Menggunakan K-means</h1>
	</div>
	<div class="row justify-content-md-center">
			<p>
				<?php foreach ($hasil_iterasi as $key => $value) { ?>
				<a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample<?php echo $key ?>" role="button" aria-expanded="false" aria-controls="multiCollapseExample<?php echo $key ?>">Iterasi ke <?php echo ($key+1); ?></a>
				<?php }  ?>
			</p>
			<!-- <div class="col"> -->
			<div class="row justify-content-md-center">
			  <div class="col">
			    <div class="collapse multi-collapse" id="multiCollapseExample">
			      <div class="card card-body">
		      		<div class="row">
		      			<div class="col justify-content-md-center">
			      			<table class="table table-bordered table-striped">
							</table>
						</div>
			      	</div>
			    	</div>
			  	</div>
				</div>
			</div>
			<?php
			foreach ($hasil_iterasi as $key => $value) { ?>
			<!-- <div class="col"> -->
			<div class="row justify-content-md-center">
			  <div class="col">
			    <div class="collapse multi-collapse" id="multiCollapseExample<?php echo $key; ?>">
			      <div class="card card-body">
		      		<h2>Iterasi ke <?php echo ($key+1) ?></h2>
		      		<div class="row">
		      			<div class="col justify-content-md-center">
			      			<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th rowspan="1" class="text-center">Centroid</th>
										<th rowspan="1" class="text-center"><?php echo $variable_x; ?></th>
										<th rowspan="1" class="text-center"><?php echo $variable_y; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($centroid[$key] as $key_c => $value_c) { ?>
									<tr>
										<td class="text-center"><?php echo ($key_c+1); ?></td>
										<td class="text-center"><?php echo $value_c[0]; ?></td>
										<td class="text-center"><?php echo $value_c[1]; ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="col justify-content-md-center">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th rowspan="2" class="text-center">Data ke i</th>
										<th rowspan="2" class="text-center">Provinsi</th>
										<th rowspan="2" class="text-center"><?php echo $variable_x; ?></th>
										<th rowspan="2" class="text-center"><?php echo $variable_y; ?></th>
										<th rowspan="1" class="text-center" colspan="<?php echo $cluster; ?>">Jarak ke centroid</th>
										<th rowspan="2" class="text-center" >Jarak terdekat</th>
										<th rowspan="2" class="text-center">Cluster</th>
									</tr>
									<tr>
									<?php for ($i=1; $i <=$cluster ; $i++) { ?> 
										<th rowspan="1" class="text-center"><?php echo $i; ?></th>
									<?php }?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($value as $key_data => $value_data) { ?>
									<tr>
										<td class="text-center"><?php echo $key_data+1; ?></td>
										<td class="text-center"><?php echo $nama[$key_data]; ?></td>
										<td class="text-center"><?php echo $value_data['data'][0]; ?></td>
										<td class="text-center"><?php echo $value_data['data'][1]; ?></td>
										<?php
										foreach ($value_data['jarak_ke_centroid'] as $key_jc => $value_jc) { ?>
											<td class="text-center"><?php echo $value_jc; ?></td>
										<?php 
										}
										?>
										<td class="text-center"><?php echo $value_data['jarak_terdekat']['value']; ?></td>
										<td class="text-center"><?php echo $value_data['jarak_terdekat']['cluster']; ?></td>
									</tr>

									<?php } ?>
								</tbody>
							</table>
						</div>
			      	</div>
			    	</div>
			  	</div>
				</div>
			</div>
			<?php
			}
			?>
	</div>
	<!-- <div class="row justify-content-md-center">
		<div id="chartContainer" style="min-width: 810px; height: 600px; max-width: 900px; margin: 0 auto"></div>
	</div>
</div>
<script type="text/javascript" src="node_modules/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="node_modules/highcharts/highcharts.js"></script>
<script type="text/javascript" src="node_modules/highcharts/highcharts-more.js"></script>
<script type="text/javascript">
var data=[];
var color=['red','green','blue'];
<?php foreach ($centroid[(count($centroid)-1)] as $key => $value) { ?>
		var dataPoints={
		    name: "Centroid <?php echo ($key+1); ?>",
		    color: 'yellow',
		    data: [{
			    x:<?php echo $value[0]; ?>,
			    y:<?php echo $value[1]; ?>
			}]
		};
		data.push(dataPoints);
<?php } ?>
<?php 
	foreach ($hasil_cluster as $key => $value) { ?>
		var dataPoints={
		    name: "Cluster <?php echo ($key+1); ?>",
		    color: color[<?php echo $key; ?>],
		    data: []
		};
<?php	for ($i=0; $i <count($value[0]) ; $i++) { ?>
	<?php	
			$nama_provinsi='';
			foreach ($data as $key_d => $value_d) { 
				if($value_d[0]==$value[0][$i] && $value_d[1]==$value[1][$i]){
					$nama_provinsi=$provinsi[$key_d];
				}
			} ?>
			dataPoints.data.push({
				name:"<?php echo $nama_provinsi; ?>",
				x:<?php echo $value[0][$i]; ?>,
				y:<?php echo $value[1][$i]; ?>
			});
<?php 	} ?>
		data.push(dataPoints);
<?php } ?>
console.log(data);
// break;
Highcharts.chart('chartContainer', {
    chart: {
        type: 'scatter',
        zoomType: 'xy'
    },
    title: {
        text: 'Klasterisasi Perbandingan Jumlah Guru dan Jumlah Siswa per Provinsi '
    },
    xAxis: {
        title: {
            enabled: true,
            text: 'stock'
        },
        startOnTick: true,
        endOnTick: true,
        showLastLabel: true
    },
    yAxis: {
        title: {
            text: 'terjual'
        }
    },
    plotOptions: {
        scatter: {
            marker: {
                radius: 5,
                states: {
                    hover: {
                        enabled: true,
                        lineColor: 'rgb(100,100,100)'
                    }
                }
            },
            states: {
                hover: {
                    marker: {
                        enabled: false
                    }
                }
            },
            tooltip: {
                headerFormat: '<b>{series.name} {point.key}</b><br>',
                pointFormat: '{point.x} stock, {point.y} terjual'
            }
        }
    },
    series: data
}); -->


</script>
<br>
</body>
</html>