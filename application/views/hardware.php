<div class="mt-20 flex mx-12" x-data="requests">
	<div class="relative shadow-md sm:rounded-lg w-full overflow-scroll" x-data="{...staticData(), ...viewOnly()}">
		<h4 class="ml-4 prose prose-lg font-bold">Liste des Matériels</h4>
		<div class="p-4 flex flex-row justify-between">
			<?php echo search_form() ?>
			<div class="flex flex-row space-x-2 hidden">
				<template x-for="category in Object.keys(categories)" :key="index">
					<button @click="selected = category" x-text="categories[category]" class="p-2 bg-white rounded-md hover:bg-tertiary" :class="selected == category && 'bg-tertiary'"></button>
				</template>
			</div>
			<button @click="modalOpen =! modalOpen" class="flex items-center justify-center px-3 py-2 space-x-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-secondary rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50">
				<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
					<path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
				</svg>
				<span>Nouveau matériel</span>
			</button>
			<?php echo modal_hardware_form($personnels) ?>
			<?php echo modal_delete_warning('
			<p>Voulez-vous vraiment supprimer le matériel suivant :
				<br />Numéro de serie : <span x-text="itemToDelete.serial_number"></span>
				<br />Modèle : <span x-text="itemToDelete.model"></span>
				<br />Couleur : <span x-text="itemToDelete.color"></span>
				<br />Catégorie : <span x-text="categories[itemToDelete.category]"></span>
			</p>
			') ?>
			<?php echo modal_personnel_form('personnelFormData', 'personnelModalOpen')?>
		</div>
		<table class="w-full text-sm text-left text-gray-500">
			<thead class="text-xs text-gray-700 uppercase bg-gray-50">
				<tr>
					<th scope="col" class="p-2">
						<span class="sr-only">Image</span>
					</th>
					<th scope="col" class="px-3 py-3">Numéro de série</th>
					<th scope="col" class="px-3 py-3">Modèle</th>
					<th scope="col" class="px-3 py-3">Couleur</th>
					<th scope="col" class="px-3 py-3">Catégorie</th>
					<th scope="col" class="px-3 py-3">Fabricant</th>
					<th scope="col" class="px-3 py-3">Date d'achat</th>
					<th scope="col" class="px-3 py-3">Expiration garantie</th>
					<th scope="col" class="px-3 py-3">État</th>
					<th scope="col" class="px-3 py-3">Date Mouvement</th>
					<th scope="col" class="px-3 py-3">
						<span class="sr-only">Edit</span>
					</th>
					<th scope="col" class="px-3 py-3">
						<span class="sr-only">Supprimer</span>
					</th>
				</tr>
			</thead>
			<tbody>
				<template x-for="item in data" :key="item.id">
					<tr class="bg-white border-b hover:bg-gray-50">
						<td class="w-12 p-2">
							<img class="max-h-10 mx-auto" x-show="item.category == 'desktop'" src="<?php echo img_url('desktop.png') ?>" />
							<img class="max-h-10 mx-auto" x-show="item.category == 'laptop'" src="<?php echo img_url('laptop.png') ?>" />
							<img class="max-h-10 mx-auto" x-show="item.category == 'phone'" src="<?php echo img_url('phone.png') ?>" />
							<img class="max-h-10 mx-auto" x-show="item.category == 'landline'" src="<?php echo img_url('landline.png') ?>" />
							<img class="max-h-10 mx-auto" x-show="item.category == 'printer'" src="<?php echo img_url('printer.png') ?>" />
							<img class="max-h-10 mx-auto" x-show="!Object.keys(categories).includes(item.category) || item.category == 'other'" src="<?php echo img_url('other.png') ?>" />
						</td>
						<td class="px-3 py-4" x-text='item.serial_number'></td>
						<td class="px-3 py-4" x-text='item.model'></td>
						<td class="px-3 py-4" x-text='item.color'></td>
						<td class="px-3 py-4" x-text='categories[item.category]'></td>
						<td class="px-3 py-4" x-text='item.manufacturer'></td>
						<td class="px-3 py-4" x-text='item.date_purchase'></td>
						<td class="px-3 py-4" x-text='item.date_warranty_expiration'></td>
						<td x-show='item.under_maintenance == 1' class="px-3 py-4">En Maintenance</td>
						<td x-show='item.under_maintenance == 0 && item.personnel' class="px-3 py-4">
							<a href="#" class="font-medium text-primary hover:underline" x-text="item.personnel?.name" @click="personnelToFormData(item.personnel); personnelModalOpen = true;"></a>
						</td>
						<td x-show='item.under_maintenance == 0 && !item.personnel' class="px-3 py-4">Disponible</td>
						<td class="px-3 py-4" x-text="item.date_movement ? item.date_movement : ''"></td>
						<td class="w-12 p-3">
							<a href="#" class="font-medium hover:text-secondary group" @click="initializeFormData(item); modalOpen = true;">
								<svg fill="currentColor" class="text-black-90 group-hover:text-secondary max-h-10 mx-auto" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
									<path d="M968.161,31.839c36.456,36.456,36.396,95.547,0,132.003l-43.991,43.991L792.138,75.83l43.991-43.991
	C872.583-4.586,931.704-4.617,968.161,31.839z M308.238,559.79l-43.96,175.963l175.963-43.991l439.938-439.938L748.147,119.821
	L308.238,559.79z M746.627,473.387v402.175H124.438V253.373h402.204l124.407-124.438H0V1000h871.064V348.918L746.627,473.387z" />
								</svg>
							</a>
						</td>
						<td class="w-12 p-3">
							<a href="#" class="font-medium hover:text-red-500 group" @click="itemToDelete = item; deleteModalOpen = true">
								<svg fill="currentColor" class="text-black-90 group-hover:text-red-500 max-h-10 mx-auto" version="1.1"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 900.5 900.5" xml:space="preserve">
									<g>
										<path d="M176.415,880.5c0,11.046,8.954,20,20,20h507.67c11.046,0,20-8.954,20-20V232.487h-547.67V880.5L176.415,880.5z
		 M562.75,342.766h75v436.029h-75V342.766z M412.75,342.766h75v436.029h-75V342.766z M262.75,342.766h75v436.029h-75V342.766z" />
										<path d="M618.825,91.911V20c0-11.046-8.954-20-20-20h-297.15c-11.046,0-20,8.954-20,20v71.911v12.5v12.5H141.874
		c-11.046,0-20,8.954-20,20v50.576c0,11.045,8.954,20,20,20h34.541h547.67h34.541c11.046,0,20-8.955,20-20v-50.576
		c0-11.046-8.954-20-20-20H618.825v-12.5V91.911z M543.825,112.799h-187.15v-8.389v-12.5V75h187.15v16.911v12.5V112.799z" />
									</g>
								</svg>
							</a>
						</td>
					</tr>
				</template>
			</tbody>
		</table>
		<?php echo pagination(
			"loading ? 'Chargement' : data.length == 0 ? 'Il n\'y a aucun matériel' : data.length > 1 ? 'Il y a ' + data.length + ' matériels' : 'Il y a un seul matériel'"
		) ?>
	</div>
</div>
<script>
	function staticData() {
		return {
			currentPage: 1,
			totalPage: 1,
			selected: null,
			categories: {
				desktop: 'Ordinateur',
				laptop: 'Ordinateur Portable',
				phone: 'Téléphone Portable',
				landline: 'Téléphone Fixe',
				printer: 'Imprimante',
				other: 'Autre'
			},
		}
	}

	function viewOnly() {
		return {
			personnelModalOpen: false,
			personnelFormData: formDataOf('personnel'),
			personnelToFormData(values) {
				valuesToFormData(this, values, 'personnel', 'personnelFormData');
			}
		}
	}

	const BASE_URL = '<?php echo base_url() . 'hardware'; ?>';
	document.addEventListener('alpine:init', () => {
		Alpine.data('requests', () => requestStruct(BASE_URL, 'hardware'));
	})
</script>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		flatpickr("#date_purchase", {
			maxDate: "today",
			locale: 'fr',
			dateFormat: "Y-m-d"
		});
		flatpickr('#date_warranty_expiration', {
			locale: 'fr',
			dateFormat: "Y-m-d"
		})
	});
</script>