<div class="row justify-content-md-center">
	<div class="row justify-content-md-center">
        <br>
			<p>
				<?php foreach ($hasil_iterasi as $key => $value) { ?>
				<a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample<?php echo $key ?>" role="button" aria-expanded="false" aria-controls="multiCollapseExample<?php echo $key ?>">Iterasi ke <?php echo ($key+1); ?></a>
				<?php }  ?>
			</p>
            <br>
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