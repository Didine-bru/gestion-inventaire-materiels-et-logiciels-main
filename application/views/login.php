<!DOCTYPE html>
<html lang="fr">

<head>
    <title>BOA</title>
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
</head>

<body class="w-screen h-screen overflow-y-auto">
    <div class="w-full h-full flex items-center justify-center">
        <div class="flex flex-col w-1/2 bg-white shadow-xl rounded-2xl px-20 py-10 space-y-8">
            <div class="space-y-2">
                <img src="<?php echo img_url("logo_lg.png"); ?>" alt="logo">
                <h1 class="text-4xl font-bold text-primary text-center uppercase">Gestion des moyens matériels et logiciels</h1>
            </div>
            <form action="<?php echo base_url() . 'auth/login'; ?>" method="post" class="flex flex-col space-y-5 items-center">
                <?php if ($message) echo "<p>" . $message . "</p>"; ?>
                <input id="<?php echo $identity['id']; ?>" name="<?php echo $identity['name']; ?>" type="<?php echo $identity['type']; ?>" value="<?php echo $identity['value']; ?>" placeholder="E-mail" class="form-input px-4 py-3 rounded-md w-full border-gray-400" required />
                <input id="<?php echo $password['id']; ?>" name="<?php echo $password['name']; ?>" type="<?php echo $password['type']; ?>" placeholder="<?php echo str_replace(':', '', lang('login_password_label')) ?>" minlength=6 class="form-input px-4 py-3 rounded-md w-full border-gray-400" required />
                <button class="w-56 h-9 bg-tertiary rounded-md uppercase" type="submit"><?php echo lang('login_submit_btn'); ?></button>
                <p class="self-end text-gray-500 hover:text-green-500"><a href="<?php echo base_url() . 'auth/forgot_password'; ?>"><?php echo lang('login_forgot_password'); ?></a></p>
            </form>
        </div>
    </div>
</body>