<?php
if ( !class_exists( 'AB_Metrika_AdminMetrica' ) ) {
	class AB_Metrika_AdminMetrica
	{
		public $yandex_metrika_token = '';
		public $yandex_metrika_counter_id = '';
		public $yandex_metrika_client_id = '598a46c6a37243b0846f9b1a965c91a9';
		public $date1 = '';
		public $date2 = '';
		public $group = '';
		public $period = '';

		public function __construct ()
		{
			$this->yandex_metrika_token = get_option('yandex_metrika_token');
			$this->yandex_metrika_counter_id = get_option('yandex_metrika_counter_id');
			//
			$period = (isset($_GET['period']) && !empty($_GET['period']))?$_GET['period']:'week';
			$group = (isset($_GET['group']) && !empty($_GET['group']))?$_GET['group']:'';
			$this->init_date($period, $group);
		}

		public function view() {
			if ('' == $this->yandex_metrika_counter_id)
			{
				$this->view_settings();
			} else {
				$this->view_data();
			}
		}

		private function view_data()
		{
			$array_url_data = array(
				'preset' => 'traffic',
				'metrics' => 'ym:s:visits,ym:s:users,ym:s:pageviews,ym:s:percentNewVisitors,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
				'group' => $this->group,
				'date1' => $this->date1,
				'date2' => $this->date2,
				'limit' => 366,
				'ids' => $this->yandex_metrika_counter_id,
				'oauth_token' => $this->yandex_metrika_token,
			);
			$url = 'https://api-metrika.yandex.ru/stat/v1/data/bytime?'. http_build_query($array_url_data);

			$json = file_get_contents($url);
			$data = json_decode($json, true);
			$view_data_title = 
			$view_data = $this->create_data($data);
			$view_data_gr = $this->create_data_columnchart($view_data);

			ob_start();
			include plugin_dir_path( __FILE__ ) . 'page-admin-metrica-view-data.php';
			$content = ob_get_clean();
			echo $content;	
		}

		public function view_settings()
		{
			ob_start();
			include plugin_dir_path( __FILE__ ) . 'page-admin-metrica-settings.php';
			$content = ob_get_clean();
			echo $content;	
		}

		public function get_metric_title ($metric_code)
		{
			$title = array(
				'ym:s:visits' => __( 'Visits', 'ab-metrika' ),
				'ym:s:pageviews' => __( 'Views', 'ab-metrika' ), 
				'ym:s:users' => __( 'Visitors', 'ab-metrika' ),
				'ym:s:percentNewVisitors' => __( 'Proportion of new visitors', 'ab-metrika' ),
				'ym:s:bounceRate' => __( 'Refusal', 'ab-metrika' ),
				'ym:s:pageDepth' => __( 'Depth of view', 'ab-metrika' ),
				'ym:s:avgVisitDurationSeconds' => __( 'Time, min.', 'ab-metrika' ), 
				);
			if (isset($title[$metric_code])) {
				return $title[$metric_code];	
			} else {
				return $metric_code;
			}	
		}

		/*
		* создаем массив для визуализации
		*/
		private function create_data_columnchart($data)
		{

			$return  = array('categories' => '', 'data' => '', 'color'=> '', 'title' => '', 'subtitle' => '');
			if ('year' == $this->period) {
				$return['title'] = "'".__( 'Visits for the year', 'ab-metrika' )."'";
			} elseif ('quarter' == $this->period) {
				$return['title'] = "'".__( 'Visits for the quarter', 'ab-metrika' )."'";
			} elseif ('month' == $this->period) {
				$return['title'] = "'".__( 'Visits for the month', 'ab-metrika' )."'";
			} elseif ('week' == $this->period) {
				$return['title'] = "'".__( 'Visits per week', 'ab-metrika' )."'";
			} else {
				if ('yesterday' == $this->period) {
					$day = date('d.m.Y', mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
				} else {
					$day = date('d.m.Y');
				}
				$return['title'] = "'".__( 'Visits', 'ab-metrika' ).' '.$day."'";
			} 
			$return['subtitle'] = "'".__( 'Source', 'ab-metrika' ).': metrika.yandex.ru'."'";
			/***/
			for ($i=0;$i<count($data);$i++) {
				if ('hour' == $data[$i]['time_intervals_type']) {
					$time_intervals_tmp = explode(' ', $data[$i]['time_intervals']);
					$time_intervals = $time_intervals_tmp[1];
				} else {
					$time_intervals = $data[$i]['time_intervals'];
				}
				$return['categories'] .= "'".$time_intervals."',";
				$return['data'] .= $data[$i]['metrics'][0].",";
				if (('day' == $data[$i]['time_intervals_type']) && ((0==$data[$i]['time_intervals_day_type']) || (6==$data[$i]['time_intervals_day_type']))){
					$return['color'] .= "'#e8b0fa',";
				} else {
					$return['color'] .= "'#c3b0fa',";
				}
			}
			return $return;
		}

		private function create_data($data)
		{
			$return = array();
			$num_metrics = count($data['query']['metrics']);
			$num_time_intervals = count($data['time_intervals']);
			for ($i_time_intervals=0;$i_time_intervals<=($num_time_intervals-1);$i_time_intervals++) {
				$return[$i_time_intervals]['time_intervals'] = $this->format_time_intervals_name(
													$data['time_intervals'][$i_time_intervals][0],
													$data['time_intervals'][$i_time_intervals][1],
													$data['query']['group']
													);
				for ($i_num_metrics=0;$i_num_metrics<$num_metrics;$i_num_metrics++) {
					$return[$i_time_intervals]['metrics'][$i_num_metrics] = $this->format_data(
						$data['totals'][$i_num_metrics][$i_time_intervals],
						$data['query']['metrics'][$i_num_metrics]);
				}
				$return[$i_time_intervals]['time_intervals_type'] = $data['query']['group'];
				if ('day' == $data['query']['group']) {
					$return[$i_time_intervals]['time_intervals_day_type'] = date('w',strtotime($data['time_intervals'][$i_time_intervals][0]));
				}
			}
			return $return;
		}

		/***
		* Преобразовываем интервал времени в зависимости от типа для красоты
		***/
		private function format_time_intervals_name ($data1, $data2, $type)
		{
			if ('day' == $type) {
				$time=strtotime($data1);
				$data1 = date('d.m.Y',$time);
				$time_intervals_name = $data1;
			} elseif ('hour' == $type) {
				$time=strtotime($data1);
				$data1 = date('d.m.Y H:i',$time);
				$time_intervals_name = $data1;
				//$time_intervals_name = $time1[1];
			} else {
				$time=strtotime($data1);
				$data1 = date('d.m.Y',$time);
				$time=strtotime($data2);
				$data2 = date('d.m.Y',$time);
				$time_intervals_name = $data1.' - '.$data2;
			}
			return $time_intervals_name;
		}

		/***
		* Преобразовываем данные в зависимости от типа для красоты
		***/
		public function format_data ($data, $type)
		{
			if (('ym:s:percentNewVisitors' == $type) ||
				('ym:s:bounceRate' == $type)) {
				$data = round($data, 1). ' %';
			} elseif ('ym:s:pageDepth' == $type) {
				$data = round($data, 2);
			} elseif ('ym:s:avgVisitDurationSeconds' == $type) {
				$data = date("i:s", mktime(0, 0, round($data)));
			}
			return $data;
		}

		/***
		* Инициализируем даты
		***/
		private function init_date($period, $group)
		{
			//$this->date2 = date('Ymd');
			$this->date2 = 'today';
			switch ($period) {
				case 'today':
					$this->date1 = 'today';
					$this->date2 = 'today';
					$this->period = 'today';
					break;

				case 'yesterday':
					$this->date1 = 'yesterday';
					$this->date2 = 'yesterday';
					$this->period = 'yesterday';
					break;

				case 'month':
					$this->date1 = date('Ymd',strtotime("-30 day"));
					$this->period = 'month';
					break;

				case 'quarter':
					$this->date1 = date('Ymd',strtotime("-90 day"));
					$this->period = 'quarter';
					break;

				case 'year':
					$this->date1 = date('Ymd',strtotime("-365 day"));
					$this->period = 'year';
					break;

				default: //по умолчанию week
					$this->date1 = date('Ymd',strtotime("-6 day"));
					$this->period = 'week';
					break;
			}

			if (('today' == $this->period) || ('yesterday' == $this->period)) {
				$this->group = 'hour';
			} elseif ((('year' == $this->period) || ('quarter' == $this->period)) && empty($group)) { //Для года и квартала по умолчанию неделя
				$this->group = 'week';
			} else {
				switch ($group) {		

					case 'week':
						$this->group = 'week';
						break;

					case 'month':
						$this->group = 'month';
						break;

					default: //по умолчанию день
						$this->group = 'day';
						break;
				}
			}
		}

		public function get_filters ()
		{
			$base_url = get_admin_url(null, 'admin.php?page=ab_metrika');
			$filter = '<div class="wp-filter"><ul class="filter-links">';
			
			$class = ('today' == $this->period)?' class="current"':'';
			$filter .= '<li><a href="'.$base_url.'&period=today"'.$class.'>'.__('Today', 'ab-metrika').'</a></li>';

			$class = ('yesterday' == $this->period)?' class="current"':'';
			$filter .= '<li><a href="'.$base_url.'&period=yesterday"'.$class.'>'.__('Yesterday', 'ab-metrika').'</a></li>';

			$class = ('week' == $this->period)?' class="current"':'';
			$filter .= '<li><a href="'.$base_url.'&period=week"'.$class.'>'.__('Week', 'ab-metrika').'</a></li>';

			$class = ('month' == $this->period)?' class="current"':'';
			$filter .= '<li><a href="'.$base_url.'&period=month"'.$class.'>'.__('Month', 'ab-metrika').'</a></li>';

			$class = ('quarter' == $this->period)?' class="current"':'';
			$filter .= '<li><a href="'.$base_url.'&period=quarter"'.$class.'>'.__('Quarter', 'ab-metrika').'</a></li>';

			$class = ('year' == $this->period)?' class="current"':'';
			$filter .= '<li><a href="'.$base_url.'&period=year"'.$class.'>'.__('Year', 'ab-metrika').'</a></li>';

			$base_url = $base_url.'&period='.$this->period;
			$filter .= '<li style="margin-left:40px;"><b>'.__('Detail', 'ab-metrika').':</b></li>';

			if (('today' == $this->period) || ('yesterday' == $this->period)) {
				$class = ('hour' == $this->group)?' class="current"':'';
				$filter .= '<li><a href="'.$base_url.'&group=hour"'.$class.'>'.__('by hours', 'ab-metrika').'</a></li>';
			}

			if (('today' != $this->period) && ('yesterday' != $this->period)) {
				$class = ('day' == $this->group)?' class="current"':'';
				$filter .= '<li><a href="'.$base_url.'&group=day"'.$class.'>'.__('by day', 'ab-metrika').'</a></li>';
			}

			if (('today' != $this->period) && ('yesterday' != $this->period) && ('day' != $this->period) && ('week' != $this->period)) {
				$class = ('week' == $this->group)?' class="current"':'';
				$filter .= '<li><a href="'.$base_url.'&group=week"'.$class.'>'.__('by week', 'ab-metrika').'</a></li>';
			}

			if (('quarter' == $this->period) || ('year' == $this->period)) {
				$class = ('month' == $this->group)?' class="current"':'';
				$filter .= '<li><a href="'.$base_url.'&group=month"'.$class.'>'.__('by months', 'ab-metrika').'</a></li>';
			}

			$filter .= '</ul>';

			$filter .= '</div>';

			return $filter;
		}

		public function get_all_counters_select()
		{
			if ('' == $this->yandex_metrika_token) {
				return false;
			} else {
				$json = file_get_contents('https://api-metrika.yandex.ru/management/v1/counters.json?oauth_token='.$this->yandex_metrika_token);
				$data = json_decode($json, true);
				if (!empty($data)) {
					$return = '<select name="yandex_metrika_counter_id">';
					foreach($data['counters'] as $key => $value) {
						$select = ($this->yandex_metrika_counter_id == $value['id'])?' selected="selected"':'';
						$return .= '<option value="'.$value['id'].'"'.$select.'>'.$value['site'].'</option>';
					}
					$return .= '</select>';
				} else {
					$return = __('The service is temporarily not available', 'ab-metrika' );
				}
				return $return;
			}
		}
	}
}