<div class="halaman">
<div class="row justify-content-md-center">
	<div class="row justify-content-md-center">
        <br>
			<p>
				<?php foreach ($hasil_iterasi as $key => $value) { ?>
				<a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample<?php echo $key ?>" role="button" aria-expanded="false" aria-controls="multiCollapseExample<?php echo $key ?>">Iterasi ke <?php echo ($key+1); ?></a>
				<?php }  ?>
			</p>
				<br>
			<?php
			foreach ($hasil_iterasi as $key => $value) { ?>
			<!-- <div class="col"> -->
			<div class="row justify-content-md-center">
			  <div class="col">
			    <div class="" id="multiCollapseExample<?php echo $key; ?>">
			      <div class="card card-body">
		      		<h2>Iterasi ke <?php echo ($key+1) ?></h2>
		      		<div class="row">
		      			<div class="col justify-content-md-center">
			      			<table class="table">
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
							<table class="table">
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
	</div>