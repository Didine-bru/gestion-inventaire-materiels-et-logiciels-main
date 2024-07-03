<html lang="fr">

<head>
    <title><?php echo $titre; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="<?php echo img_url("favicon-32x32.png"); ?>" sizes="32x32" />
    <link rel="icon" href="<?php echo img_url("favicon-192x192.png"); ?>" sizes="192x192" />
    <link rel="apple-touch-icon" href="<?php echo img_url("favicon-180x180.png"); ?>" />
    <!-- <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script> -->
    <script src="<?php echo js_url("tailwindcss-3.2") ?>"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#008457',
                        secondary: '#9b51e0',
                        tertiary: '#0693e3'
                    },
                    fontFamily: {
                        sans: ['Lato', 'Roboto']
                    }
                }
            },
        }
    </script>
    <!-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    <script type="application/javascript" src="<?php echo js_url("alpine@3.11.1-cdn.min"); ?>" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <?php foreach ($css as $url) : ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
    <?php endforeach; ?>
</head>

<body x-data="{baseUrl: '<?php echo base_url() ?>', isHome: <?php if (!in_array('home', explode('/', strtolower(uri_string()))) && uri_string() != '') echo 0;
                                                            else echo 1 ?> == 1}" class="bg-gray-100">
    <div class="w-full h-full flex flex-col">
        <div class="w-full text-gray-700 bg-white">
            <div x-data="{ open: true }" class="flex flex-col max-w-screen-xl px-4 mx-auto md:items-center md:justify-between md:flex-row md:px-6 lg:px-8">
                <div class="flex flex-row items-center justify-between p-4">
                    <a x-bind:href="baseUrl" class="text-lg font-semibold tracking-widest text-gray-900 uppercase rounded-lg focus:outline-none focus:shadow-outline">
                        <img x-show="open" src="<?php echo img_url('logo_lg.png') ?>" class="w-72 max-h-12" alt="BOA" />
                        <img x-show="!open" src="<?php echo img_url('logo_madagascar.png') ?>" class="h-14" alt="BOA" />
                    </a>
                    <button class="rounded-lg md:hidden focus:outline-none focus:shadow-outline" @click="open = !open">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                            <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <?php if ($user->is_admin) { ?>
                    <nav x-data="{urls : [{path: 'hardware', text: 'Materiels'}, {path: 'software', text: 'Logiciels'}, {path: 'license', text: 'Licences'}, {path: 'personnel', text: 'Personnels'}, {path: 'administrator', text: 'Administateurs'}]}" :class="{'flex': open, 'hidden': !open}" class="flex-col flex-grow hidden pb-4 md:pb-0 md:flex md:justify-end md:flex-row">
                    <?php } else { ?>
                        <nav x-data="{urls : [{path: 'hardware', text: 'Materiels'}, {path: 'software', text: 'Logiciels'}, {path: 'license', text: 'Licences'}]}" :class="{'flex': open, 'hidden': !open}" class="flex-col flex-grow hidden pb-4 md:pb-0 md:flex md:justify-end md:flex-row">
                        <?php } ?>
                        <template x-for="url in urls" :key="index">
                            <a x-show="!isHome" class="px-4 py-2 mt-2 text-sm font-semibold bg-transparent rounded-lg md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" :class="window.location.pathname.toLowerCase().match(url.path) && 'text-tertiary'" x-bind:href="baseUrl + url.path" x-text="url.text"></a>
                        </template>
                        <div @click.away="open = false" class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex flex-row text-primary bg-gray-200 items-center w-full px-4 py-2 mt-2 text-sm font-semibold text-left bg-transparent rounded-lg md:w-auto md:inline md:mt-0 md:ml-4 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline">
                                <span><?php echo $user->first_name . ' ' . $user->last_name; ?></span>
                                <svg fill="currentColor" viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}" class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 w-full md:max-w-screen-sm md:w-max mt-2 origin-top-right z-10">
                                <div class="px-2 pt-2 pb-4 bg-white rounded-md shadow-lg">
                                    <div class="grid grid-cols-1">
                                        <a class="hidden flex flex-row items-start rounded-lg bg-transparent p-2 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#">
                                            <svg fill="currentColor" viewBox="0 0 20 20" class="w-5 h-5 mr-3">
                                                <path d="M16 7a5.38 5.38 0 0 0-4.46-4.85C11.6 1.46 11.53 0 10 0S8.4 1.46 8.46 2.15A5.38 5.38 0 0 0 4 7v6l-2 2v1h16v-1l-2-2zm-6 13a3 3 0 0 0 3-3H7a3 3 0 0 0 3 3z" />
                                            </svg>
                                            <div class="flex flex-row space-x-10">
                                                <span>Notification(s)</span>
                                                <span class="text-gray-300">Aucune</span>
                                            </div>
                                        </a>
                                        <a class="flex flex-row items-start rounded-lg bg-transparent p-2 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" href="#" @click="console.log('software')">
                                            <svg fill="currentColor" class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 472.615 472.615" xml:space="preserve">
                                                <g>
                                                    <g>
                                                        <circle cx="236.308" cy="117.504" r="111.537" />
                                                    </g>
                                                </g>
                                                <g>
                                                    <g>
                                                        <path d="M369,246.306c-1.759-1.195-5.297-3.493-5.297-3.493c-28.511,39.583-74.993,65.402-127.395,65.402
			c-52.407,0-98.894-25.825-127.404-65.416c0,0-2.974,1.947-4.451,2.942C41.444,288.182,0,360.187,0,441.87v24.779h472.615V441.87
			C472.615,360.549,431.538,288.822,369,246.306z" />
                                                    </g>
                                                </g>
                                            </svg>
                                            <span>Profil</span>
                                        </a>
                                        <a class="flex flex-row items-start rounded-lg bg-transparent p-2 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline" x-bind:href="baseUrl + 'auth/logout'">
                                            <svg fill="currentColor" class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22 6.62219V17.245C22 18.3579 21.2857 19.4708 20.1633 19.8754L15.0612 21.7977C14.7551 21.8988 14.449 22 14.0408 22C13.5306 22 12.9184 21.7977 12.4082 21.4942C12.2041 21.2918 11.898 21.0895 11.7959 20.8871H7.91837C6.38776 20.8871 5.06122 19.6731 5.06122 18.0544V17.0427C5.06122 16.638 5.36735 16.2333 5.87755 16.2333C6.38776 16.2333 6.69388 16.5368 6.69388 17.0427V18.0544C6.69388 18.7626 7.30612 19.2684 7.91837 19.2684H11.2857V4.69997H7.91837C7.20408 4.69997 6.69388 5.20582 6.69388 5.91401V6.9257C6.69388 7.33038 6.38776 7.73506 5.87755 7.73506C5.36735 7.73506 5.06122 7.33038 5.06122 6.9257V5.91401C5.06122 4.39646 6.28572 3.08125 7.91837 3.08125H11.7959C12 2.87891 12.2041 2.67657 12.4082 2.47423C13.2245 1.96838 14.1429 1.86721 15.0612 2.17072L20.1633 4.09295C21.1837 4.39646 22 5.50933 22 6.62219Z" fill="#030D45" />
                                                <path d="M4.85714 14.8169C4.65306 14.8169 4.44898 14.7158 4.34694 14.6146L2.30612 12.5912C2.20408 12.49 2.20408 12.3889 2.10204 12.3889C2.10204 12.2877 2 12.1865 2 12.0854C2 11.9842 2 11.883 2.10204 11.7819C2.10204 11.6807 2.20408 11.5795 2.30612 11.5795L4.34694 9.55612C4.65306 9.25261 5.16327 9.25261 5.46939 9.55612C5.77551 9.85963 5.77551 10.3655 5.46939 10.669L4.7551 11.3772H8.93878C9.34694 11.3772 9.7551 11.6807 9.7551 12.1865C9.7551 12.6924 9.34694 12.7936 8.93878 12.7936H4.65306L5.36735 13.5017C5.67347 13.8052 5.67347 14.3111 5.36735 14.6146C5.26531 14.7158 5.06122 14.8169 4.85714 14.8169Z" fill="#030D45" />
                                            </svg>
                                            <span>Se Déconnecter</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </nav>
            </div>
        </div>

        <?php echo $output; ?>
    </div>
    <?php foreach ($js as $url) : ?>
        <script type="text/javascript" src="<?php echo $url; ?>"></script>
    <?php endforeach; ?>
</body>

</html>