<div class="flex grow items-center justify-center">
	<?php if ($user->is_admin) { ?>
		<div class="grid grid-rows-2 space-y-4">
			<div class="grid grid-cols-5">
				<div></div>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'hardware'">
					<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('hardwares.png'); ?>" alt="hardwares" class="object-contain w-28 h-28" />
					</div>
					<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Matériels</p>
				</a>
				<div></div>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'software'">
					<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('softwares.png'); ?>" alt="softwares" class="object-contain w-28 h-28" />
					</div>
					<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Logiciels</p>
				</a>
				<div></div>
			</div>
			<div class="grid grid-cols-5">
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'administrator'">
					<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('administrators.png'); ?>" alt="administrators" class="object-contain w-28 h-28" />
					</div>
					<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Administrateurs</p>
				</a>
				<div></div>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'personnel'">
					<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('personnels.png'); ?>" alt="personnels" class="object-contain w-28 h-28" />
					</div>
					<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Personnels</p>
				</a>
				<div></div>
				<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'license'">
					<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
						<img src="<?php echo img_url('licenses.png'); ?>" alt="licenses" class="object-contain w-28 h-28" />
					</div>
					<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Licences</p>
				</a>
			</div>
		</div>
	<?php } else { ?>
		<div class="grid grid-cols-3 space-x-12">
			<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'hardware'">
				<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
					<img src="<?php echo img_url('hardwares.png'); ?>" alt="hardwares" class="object-contain w-28 h-28" />
				</div>
				<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Matériels</p>
			</a>
			<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'software'">
				<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
					<img src="<?php echo img_url('softwares.png'); ?>" alt="softwares" class="object-contain w-28 h-28" />
				</div>
				<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Logiciels</p>
			</a>
			<a class="flex flex-col items-center space-y-3.5 group" x-bind:href="baseUrl + 'license'">
				<div class="w-52 h-44 bg-white group-hover:bg-tertiary rounded-full flex items-center justify-center">
					<img src="<?php echo img_url('licenses.png'); ?>" alt="licenses" class="object-contain w-28 h-28" />
				</div>
				<p class="text-2xl font-bold text-black group-hover:text-tertiary text-opacity-90">Licences</p>
			</a>
		</div>
	<?php } ?>
</div>