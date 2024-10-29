<div class="wrap">
	<h2><?php _e('Traffic', 'ab-metrika') ?></h2>

	<?php echo $this->get_filters(); ?>

	<div id="container" style="min-width:310px;height:400px;margin:10px auto 20px;"></div>
	<script type="text/javascript">
		Highcharts.chart('container', {
			chart: {type:'column'},
			title: {text:<?php echo $view_data_gr['title']; ?>},
			subtitle: {text:<?php echo $view_data_gr['subtitle']; ?>},
			xAxis: {categories:[<?php echo $view_data_gr['categories']; ?>],crosshair: true},
			yAxis: {min: 0,title: {text: '<?php _e( 'Visits', 'ab-metrika' ) ?>'}},
			tooltip: {
				headerFormat:'<span style="font-size:10px">{point.key}</span><table>',
				pointFormat:'<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
				footerFormat:'</table>',
				shared:true,
				useHTML:true
			},
			plotOptions:{column:{pointPadding:0.2,borderWidth:0,colors:[<?php echo $view_data_gr['color']; ?>],}},
			series:[{name:'<?php _e( 'Visits', 'ab-metrika' ) ?>',colorByPoint: true,data: [ <?php echo $view_data_gr['data']; ?> ],}]
		});
	</script>
	
	<table class="wp-list-table widefat fixed striped pages">
		<thead>
			<tr>
				<th></th>
				<?php foreach ($data['query']['metrics'] as $key => $value): ?>
					<th><?php echo $this->get_metric_title($value); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
		<?php 
			$max_element = count($view_data)-1;
			for($i=0;$i<=$max_element;$i++) : 
				if ('hour' != $view_data[$i]['time_intervals_type']) { //если не часы надо отобразить данные в обратном порядке
					$i_num = $max_element-$i;
				} else {
					$i_num = $i;
				}?>
			<tr>
				<?php 
					if (('day' == $view_data[$i_num]['time_intervals_type']) && 
						((0==$view_data[$i_num]['time_intervals_day_type']) || (6==$view_data[$i_num]['time_intervals_day_type']))){
						$style = ' style="color:red;"';
					} else {
						$style ='';
					} ?>
				<td<?php echo $style; ?>><?php echo $view_data[$i_num]['time_intervals'];?>
			 	</td>
				<?php foreach ($view_data[$i_num]['metrics'] as $key => $value) : ?>
					<td><?php echo $value; ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endfor; ?>
		</tbody>
	</table>
</div>