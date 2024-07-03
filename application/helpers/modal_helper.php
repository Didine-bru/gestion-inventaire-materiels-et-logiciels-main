<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function modal_delete_warning($context)
{
  return '
      <div x-cloak x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-init="$watch(\'deleteModalOpen\', (value) => {if (!value) itemToDelete = null; })">
				<div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
					<div x-cloak @click="deleteModalOpen = false" x-show="deleteModalOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"></div>

					<div x-cloak x-show="deleteModalOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl">
						<div class="flex items-center justify-between space-x-4">
							<h1 class="text-xl font-medium text-gray-800">Supprimer un administrateur</h1>

							<button @click="deleteModalOpen = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
							</button>
						</div>
						<div class="flex mt-5">
							<template x-if="itemToDelete != null">' . $context . '</template>
						</div>
						<div class="flex flex-row justify-end mt-5">
							<button class="px-3 py-2 text-sm tracking-wide text-black-75 uppercase border-gray-300 rounded-md hover:bg-gray-300" @click="deleteModalOpen = false">Annuler</button>
							<button class="px-3 py-2 text-sm tracking-wide text-red-500 uppercase border-gray-300 rounded-md hover:bg-gray-300" @click="deleteItem()">Supprimer</button>
						</div>
					</div>
				</div>
			</div>
  ';
}

function modal_form_container($title, $form, $submit_btn_text, $modalOpen = 'modalOpen', $readOnly = FALSE)
{
  if ($readOnly)
    return '
    <div x-cloak x-show="' . $modalOpen . '" x-init="$watch(\'modalOpen\', (value) => {if (!value) initializeFormData() })" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
        <div x-cloak @click="' . $modalOpen . ' = false" x-show="' . $modalOpen . '" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"></div>

        <div x-cloak x-show="' . $modalOpen . '" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl">
          <div class="flex items-center justify-between space-x-4">
            <h1 class="text-xl font-medium text-gray-800" x-text="' . $title . '"></h1>
            <button @click="' . $modalOpen . ' = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </button>
          </div>
          <form class="mt-5" @submit.prevent="submitForm">
            <div x-show="formMessage != \'\'" class="mb-4 text-red-500" x-html="formMessage"></div>
            ' . $form . '
          </form>
        </div>
      </div>
    </div>
    ';
  return '
      <div x-cloak x-show="' . $modalOpen . '" x-init="$watch(\'modalOpen\', (value) => {if (!value) initializeFormData() })" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
				<div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
					<div x-cloak @click="' . $modalOpen . ' = false" x-show="' . $modalOpen . '" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"></div>

					<div x-cloak x-show="' . $modalOpen . '" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl">
						<div class="flex items-center justify-between space-x-4">
              <h1 class="text-xl font-medium text-gray-800" x-text="' . $title . '"></h1>
							<button @click="' . $modalOpen . ' = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
								<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
							</button>
						</div>
						<form class="mt-5" @submit.prevent="submitForm">
							<div x-show="formMessage != \'\'" class="mb-4 text-red-500" x-html="formMessage"></div>
							' . $form . '
							<div class="flex justify-end mt-6">
								<button type="submit" :disabled="formLoading" x-text="' . $submit_btn_text . '" class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-secondary rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-500 focus:ring focus:ring-indigo-300 focus:ring-opacity-50"></button>
							</div>
						</form>
					</div>
				</div>
			</div>
  ';
}

function modal_administrator_form($formData = 'formData', $modalOpen = 'modalOpen')
{
  $readOnly = $modalOpen != 'modalOpen' ? 'readonly' : '';

  $form = '
  <div>
    <label for="identity" class="block text-sm text-gray-700 capitalize">Identifiant</label>
    <input x-model="' . $formData . '.identity" ' . $readOnly . ' name="identity" id="identity" placeholder="identifiant" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
  </div>
  <div class="mt-4">
    <label for="first_name" class="block text-sm text-gray-700 capitalize">Prénom(s)</label>
    <input x-model="' . $formData . '.first_name" ' . $readOnly . ' id="first_name" id="first_name" placeholder="Prénom(s)" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
  </div>
  <div class="mt-4">
    <label for="last_name" class="block text-sm text-gray-700 capitalize">Nom de famille</label>
    <input x-model="' . $formData . '.last_name" ' . $readOnly . ' id="last_name" id="last_name" placeholder="Nom de famille" type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
  </div>
  <div class="mt-4">
    <label for="email" class="block text-sm text-gray-700 capitalize">E-mail</label>
    <input x-model="' . $formData . '.email" ' . $readOnly . ' id="email" id="email" placeholder="****@exemple.com" required type="email" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
  </div>
  <div class="mt-4">
    <label for="password" class="block text-sm text-gray-700 capitalize">Mot de Passe</label>
    <input x-model="' . $formData . '.password" ' . $readOnly . ' id="password" id="password" placeholder="Mot de passe" x-bind:required="!isEdit" type="password" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
  </div>
  <div class="mt-4">
    <label for="password_confirm" class="block text-sm text-gray-700 capitalize">Confirmation du Mot de Passe</label>
    <input x-model="' . $formData . '.password_confirm" ' . $readOnly . ' id="password_confirm" id="password_confirm" placeholder="Mot de Passe" x-bind:required="!isEdit" type="password" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
  </div>
  <div class="mt-4 flex items-center space-x-3 cursor-pointer" @click="' . $formData . '.is_admin = !' . $formData . '.is_admin">
    <p class="text-gray-500">Est-ce un compte super administateur ?</p>
    <div class="relative w-10 h-5 transition duration-200 ease-linear rounded-full" :class="[' . $formData . '.is_admin ? \'bg-indigo-500\' : \'bg-gray-300\']">
      <label for="is_admin" @click="' . $formData . '.is_admin = !' . $formData . '.is_admin" class="absolute left-0 w-5 h-5 mb-2 transition duration-100 ease-linear transform bg-white border-2 rounded-full cursor-pointer" :class="[' . $formData . '.is_admin ? \'translate-x-full border-indigo-500\' : \'translate-x-0 border-gray-300\']"></label>
      <input x-model="' . $formData . '.is_admin" ' . $readOnly . ' type="checkbox" name="is_admin" class="hidden w-full h-full rounded-full appearance-none active:outline-none focus:outline-none" />
    </div>
  </div>
  ';

  return modal_form_container(
    "isEdit ? 'Modifier l\'administrateur': 'Ajouter un nouveau administrateur'",
    $form,
    "isEdit ? 'Modifier l\'administrateur': 'Ajouter l\'administrateur'",
    $modalOpen,
    $modalOpen != 'modalOpen'
  );
}


function modal_personnel_form($formData = 'formData', $modalOpen = 'modalOpen')
{
  $readOnly = $modalOpen != 'modalOpen' ? 'readonly' : '';
	$disabeld = $modalOpen != 'modalOpen' ? 'disabled' : '';

  $form = '
  <div>
		<label for="serial_number" class="block text-sm text-gray-700 capitalize">Numéro Matricule</label>
		<input x-model="' . $formData . '.serial_number" ' . $readOnly . ' name="serial_number" id="serial_number" placeholder="1B06-4H10-0491" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
	  <label for="name" class="block text-sm text-gray-700 capitalize">Nom et Prénom(s) </label>
		<input x-model="' . $formData . '.name" ' . $readOnly . ' name="name" id="name" placeholder="Nom et Prénom(s)" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="departement" class="block text-sm text-gray-700 capitalize">Département auxquel il travail</label>
		<select x-model="' . $formData . '.departement" ' . $disabeld . ' id="departement" name="department" required class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
		  <option value="" default>Choisir un département</option>
		  <option value="it_service">Service Informatique</option>
		  <option value="customer_service">Service Clientèle</option>
		  <option value="other">Autre</option>
		</select>
	</div>
	<div class="mt-4">
		<label for="post" class="block text-sm text-gray-700 capitalize">Poste occupé au sein du département</label>
		<input x-model="' . $formData . '.post"' . $readOnly . ' name="post" id="post" placeholder="Poste occupé" type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
  ';

  return modal_form_container(
    $modalOpen != 'modalOpen' ? "'Détail du personnel'" : "isEdit ? 'Modifier le personnel': 'Ajouter un nouveau personnel'",
    $form,
    "isEdit ? 'Modifier le Personnel': 'Ajouter le Personnel'",
    $modalOpen,
    $modalOpen != 'modalOpen'
  );
}

function modal_hardware_form($personnels, $formData = 'formData', $modalOpen = 'modalOpen')
{
  $readOnly = $modalOpen != 'modalOpen' ? 'readonly' : '';
  $options = '';
  foreach ($personnels as $personnel) {
    $options .= '<option value="' . $personnel->id . '">' . $personnel->name . '</option>';
  }

  $form = '
  <div>
		<label for="serial_number" class="block text-sm text-gray-700 capitalize">Numéro de série</label>
		<input x-model="' . $formData . '.serial_number" ' . $readOnly . ' name="serial_number" id="serial_number" placeholder="1B064H10491" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="model" class="block text-sm text-gray-700 capitalize">Modèle du matériel</label>
		<input x-model="' . $formData . '.model" ' . $readOnly . ' id="model" id="model" placeholder="Microsoft Surface Pro 7" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="color" class="block text-sm text-gray-700 capitalize">Couleur du matériel</label>
		<input x-model="' . $formData . '.color" ' . $readOnly . ' id="color" name="color" placeholder="Gris" type="text" required class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="category" class="block text-sm text-gray-700 capitalize">Catégorie du matériel</label>
		<select x-model="' . $formData . '.category" ' . $readOnly . ' id="category" name="category" required class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
			<option disabled selected>Choisir une catégorie</option>
			<template x-for="category in Object.keys(categories)" :key="category">
				<option :value="category" x-text="categories[category]"></option>
			</template>
		</select>
	</div>
  <div class="mt-4">
		<label for="manufacturer" class="block text-sm text-gray-700 capitalize">Fabricant</label>
		<input x-model="' . $formData . '.manufacturer" ' . $readOnly . ' id="manufacturer" name="manufacturer" placeholder="Microsoft" type="text" required class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="date_purchase" class="block text-sm text-gray-700 capitalize">Date d\'Achat</label>
		<input x-model="' . $formData . '.date_purchase" ' . $readOnly . ' name="date_purchase" id="date_purchase" placeholder="Sélectionner une date" required readonly type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="date_warranty_expiration" class="block text-sm text-gray-700 capitalize">Date d\'Expriation de la Garantie</label>
		<input x-model="' . $formData . '.date_warranty_expiration" ' . $readOnly . ' name="date_warranty_expiration" id="date_warranty_expiration" placeholder="Sélectionner une date" required readonly type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>

	<div class="mt-4 flex items-center space-x-3 cursor-pointer" @click="formData.under_maintenance = !formData.under_maintenance">
		<p class="text-sm text-gray-700">En Maintenance</p>
	  <div class="relative w-10 h-5 transition duration-200 ease-linear rounded-full" :class="[formData.under_maintenance ? \'bg-indigo-500\' : \'bg-gray-300\']">
			<label for="under_maintenance" @click="formData.under_maintenance =!formData.under_maintenance" class="absolute left-0 w-5 h-5 mb-2 transition duration-100 ease-linear transform bg-white border-2 rounded-full cursor-pointer" :class="[formData.under_maintenance ? \'translate-x-full border-indigo-500\' : \'translate-x-0 border-gray-300\']"></label>
			<input x-model="' . $formData . '.under_maintenance" ' . $readOnly . ' type="checkbox" name="under_maintenance" class="hidden w-full h-full rounded-full appearance-none active:outline-none focus:outline-none" />
		</div>
	</div>
	<div class="mt-4" x-show="!' . $formData . '.under_maintenance">
		<label for="personnel" class="block text-sm text-gray-700 capitalize">Personnel qui va utiliser le matériel</label>
		<select x-model="' . $formData . '.personnel_id" ' . $readOnly . ' id="personnel" name="personnel" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
		  <option value="" selected>Aucun</option>
				' . $options . '
			</select>
	</div>
  ';

  return modal_form_container(
    $modalOpen != 'modalOpen' ? "'Détail du matériel'" : "isEdit ? 'Modifier le matériel': 'Ajouter un nouveau matériel'",
    $form,
    "isEdit ? 'Modifier le Matériel': 'Ajouter le Matériel'",
    $modalOpen,
    $modalOpen != 'modalOpen'
  );
}


function modal_software_form($personnels, $licenses, $formData = 'formData', $modalOpen = 'modalOpen')
{
  $readOnly = $modalOpen != 'modalOpen' ? 'readonly' : '';
	$disabeld = $modalOpen != 'modalOpen' ? 'disabled' : '';

  $personnel_options = '';
  foreach ($personnels as $personnel) {
    $personnel_options .= '<option value="' . $personnel->id . '">' . $personnel->name . '</option>';
  }
  $license_options = '';
  foreach ($licenses as $license) {
    $license_options .= '<option value="' . $license->id . '">' . $license->name . '</option>';
  }

  $form = '
  <div>
		<label for="name" class="block text-sm text-gray-700 capitalize">Nom du logiciel</label>
		<input x-model="' . $formData . '.name" ' . $readOnly . ' name="name" id="name" placeholder="Nom" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="version" class="block text-sm text-gray-700 capitalize">Version du logiciel</label>
		<input x-model="' . $formData . '.version" ' . $readOnly . ' id="version" id="version" placeholder="Version" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="date_purchase" class="block text-sm text-gray-700 capitalize">Date d\'Achat</label>
		<input x-model="' . $formData . '.date_purchase" ' . $readOnly . ' name="date_purchase" id="date_purchase" placeholder="Sélectionner une date" required readonly type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="date_warranty_expiration" class="block text-sm text-gray-700 capitalize">Date d\'Expiration de la Garantie</label>
		<input x-model="' . $formData . '.date_warranty_expiration" ' . $readOnly . ' name="date_warranty_expiration" id="date_warranty_expiration" placeholder="Sélectionner une date" required readonly type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="license" class="block text-sm text-gray-700 capitalize">Licence</label>
		<select x-model="' . $formData . '.license_id" ' . $disabeld . ' id="license" name="license" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
		  <option value="" selected>Pas de license</option>
			' . $license_options . '
		</select>
	</div>
	<div class="mt-4">
		<label for="personnel" class="block text-sm text-gray-700 capitalize">Personnels qui vont utiliser le logiciel</label>
		<select x-model="' . $formData . '.personnel_ids" ' . $disabeld . ' id="personnel" name="personnel" multiple class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
      ' . $personnel_options . '
		</select>
	</div>					
  ';

  return modal_form_container(
    $modalOpen != 'modalOpen' ? "'Détail du logiciel'" : "isEdit ? 'Modifier le logiciel': 'Ajouter un nouveau logiciel'",
    $form,
    "isEdit ? 'Modifier le Logiciel': 'Ajouter le Logiciel'",
    $modalOpen,
    $modalOpen != 'modalOpen'
  );
}


function modal_license_form($formData = 'formData', $modalOpen = 'modalOpen')
{
  $readOnly = $modalOpen != 'modalOpen' ? 'readonly' : '';
  $form = '
  <div>
	  <label for="name" class="block text-sm text-gray-700 capitalize">Nom de la licence</label>
		<input x-model="' . $formData . '.name" ' . $readOnly . ' name="name" id="name" placeholder="Nom" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="license_number" class="block text-sm text-gray-700 capitalize">Numéro de la licence</label>
		<input x-model="' . $formData . '.license_number" ' . $readOnly . ' id="license_number" placeholder="Numéro" required type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="number_of_licenses" class="block text-sm text-gray-700 capitalize">Nombre de licences</label>
		<input x-model="' . $formData . '.number_of_licenses" ' . $readOnly . ' name="number_of_licenses" id="number_of_licenses" type="number" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>
	<div class="mt-4">
		<label for="date_expiration" class="block text-sm text-gray-700 capitalize">Date d\'Expriation</label>
		<input x-model="' . $formData . '.date_expiration" ' . $readOnly . ' name="date_expiration" id="date_expiration" placeholder="Sélectionner une date" readonly type="text" class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-40">
	</div>						
  ';

  return modal_form_container(
    $modalOpen != 'modalOpen' ? "'Détail de la licence'" : "isEdit ? 'Modifier la licence': 'Ajouter une nouvelle licence'",
    $form,
    "isEdit ? 'Modifier la Licence': 'Ajouter la Licence'",
    $modalOpen,
    $modalOpen != 'modalOpen'
  );
}
