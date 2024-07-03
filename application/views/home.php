<div class="flex grow items-center justify-center my-8">
	<div class="flex flex-col space-y-8">
		<?php if ($user->is_admin) { ?>
			<div class="grid grid-cols-5 space-x-8 px-8">
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'hardware'">
					<div class="w-44 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('hardwares.png'); ?>" alt="hardwares" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Matériels</p>
				</a>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'software'">
					<div class="w-44 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('softwares.png'); ?>" alt="softwares" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Logiciels</p>
				</a>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'license'">
					<div class="w-44 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('licenses.png'); ?>" alt="licenses" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Licences</p>
				</a>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'personnel'">
					<div class="w-44 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('personnels.png'); ?>" alt="personnels" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Personnels</p>
				</a>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'administrator'">
					<div class="w-48 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('administrators.png'); ?>" alt="administrators" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Administrateurs</p>
				</a>
			</div>
		<?php } else { ?>
			<div class="grid grid-cols-3 space-x-12 px-8">
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'hardware'">
					<div class="w-44 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('hardwares.png'); ?>" alt="hardwares" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Matériels</p>
				</a>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'software'">
					<div class="w-44 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('softwares.png'); ?>" alt="softwares" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Logiciels</p>
				</a>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'license'">
					<div class="w-44 h-40 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('licenses.png'); ?>" alt="licenses" class="object-contain w-20 h-20" />
					</div>
					<p class="text-xl font-bold text-black group-hover:text-tertiary text-opacity-90">Licences</p>
				</a>
			</div>
		<?php } ?>
		<div class="grid grid-cols-3 space-x-8 px-8 w-full min-h-[10rem]" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-data="requests">
			<div class="bg-white rounded-t-2xl flex flex-col items-center py-2 shadow-sm">
				<div id="hardware_chart"></div>
			</div>
			<div class="bg-white rounded-t-2xl flex flex-col items-center py-2 shadow-sm">
				<div id="software_chart"></div>
			</div>
			<div class="bg-white rounded-t-2xl flex flex-col items-center py-2 shadow-sm">
				<div id="license_chart"></div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo js_url('apexcharts') ?>"></script>
<script>
	const BASE_URL = '<?php echo base_url(); ?>';
	document.addEventListener('alpine:init', () => {
		Alpine.data('requests', () => ({
			// data: {},
			getStatistics() {
				fetch(`${BASE_URL}/home/statistics`, {
						method: "GET",
					})
					.then((response) => response.json())
					.then((response) => {
						console.log(response.data);
						let data = response.data;

						var options = {
							series: [
								data.hardware.total - (data.hardware.used + data.hardware.maintenance),
								data.hardware.used,
								data.hardware.maintenance
							],
							chart: {
								type: 'donut',
							},
							labels: ["Disponible", "Utilisé", "Maintenance"],
							colors: [
								"rgba(16,150,24,0.85)",
								"rgba(51,102,204,0.85)",
								"rgba(220,57,18,0.85)",
							],
							title: {
								text: "Matériels"
							},
							responsive: [{
								breakpoint: 480,
								options: {
									chart: {
										// width: 200
									},
									legend: {
										position: 'bottom'
									}
								}
							}]
						};

						var hardwareChart = new ApexCharts(document.querySelector("#hardware_chart"), options);
						hardwareChart.render();

						var barOptions = {
							chart: {
								// height: 350,
								type: 'bar',
								toolbar: {
									show: false
								}
							},
							plotOptions: {
								bar: {
									borderRadius: 10,
									distributed: true,
									dataLabels: {
										position: 'top', // top, center, bottom
									},
								}
							},
							legend: {
								show: false
							},
							xaxis: {
								categories: ["Expiré", "Valide"],
								position: 'bottom',
								axisBorder: {
									show: false
								},
								axisTicks: {
									show: false
								},
								crosshairs: {
									fill: {
										type: 'gradient',
										gradient: {
											colorFrom: '#D8E3F0',
											colorTo: '#BED1E6',
											stops: [0, 100],
											opacityFrom: 0.4,
											opacityTo: 0.5,
										}
									}
								},
								tooltip: {
									enabled: true,
								}
							},
							yaxis: {
								axisBorder: {
									show: false
								},
								axisTicks: {
									show: false,
								},
								labels: {
									show: false,
									formatter: function(val) {
										return val;
									}
								}

							},
						};

						var softwareChart = new ApexCharts(document.querySelector("#software_chart"), {
							...barOptions,
							series: [{
								name: 'Nombre',
								data: [data.software.expired, data.software.total - data.software.expired]
							}],
							title: {
								text: 'Logiciels',
							},
							colors: [
								"#dc3912",
								"#3366cc",
							],
						});
						softwareChart.render();

						var licenseChart = new ApexCharts(document.querySelector("#license_chart"), {
							...barOptions,
							series: [{
								name: 'Nombre',
								data: [data.license.expired, data.license.total - data.license.expired]
							}],
							title: {
								text: 'Licences',
							},
							colors: [
								"#dc3912",
								"#109618",
							],
						});
						licenseChart.render();
					});
			},
			init() {
				this.getStatistics();
			},
		}));
	})



	var data = [{
		name: 'Nombre',
		data: [23, 43]
	}]
</script>