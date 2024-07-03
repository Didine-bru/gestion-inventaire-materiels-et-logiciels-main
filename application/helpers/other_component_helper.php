<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function search_form() {
  return '
      <form @submit.prevent="submitSearch" x-init="$watch(\'searchText\', (value) => { if (!value) getData(); })">
				<label for="table-search" class="sr-only">Search</label>
				<div class="relative mt-1">
					<button class="absolute inset-y-0 left-0 flex items-center pl-3 group" type="submit">
						<svg class="w-5 h-5 text-gray-500 group-hover:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
						</svg>
					</button>
					<input x-model="searchText" type="text" id="table-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5" placeholder="Recheche d\'un administrateur">
				</div>
	    </form>
  ';
}

function pagination($text) {
	return '
		<div x-cloak class="bg-white flex flex-row items-center justify-center rounded-b-lg py-5" x-data="{p : currentPage - 3, n: (currentPage < totalPage) ? (currentPage + 3) : currentPage}">
			<span class="absolute left-0 ml-3 text-gray-400" x-text="' . $text . '"></span>
			<div x-show="totalPage > 1" class="flex flex-row">
				<a x-show="currentPage > 1" href="#" class="border border-gray-300 text-gray-500 hover:bg-gray-100 hover:text-gray-700 ml-0 rounded-l-lg leading-tight py-2 px-3">Précédent</a>
				<template x-for="i in 3">
					<a x-show="p + i > 0 && p + i <= currentPage" href="#" class="bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 leading-tight py-2 px-3" :class="p + i == currentPage ? \'text-primary\' : \'text-gray-500\'" x-text="p + i"></a>
				</template>
				<template x-for="i in totalPage - currentPage">
					<a x-show="currentPage + i <= n && currentPage + i <= totalPage" href="#" class="bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 leading-tight py-2 px-3" x-text="currentPage + i"></a>
				</template>
				<a x-show="currentPage < totalPage" href="#" class="bg-white border border-gray-300 text-gray-500 hover:bg-gray-100 hover:text-gray-700 rounded-r-lg leading-tight py-2 px-3">Suivant</a>
			</div>
		</div>
	';
}