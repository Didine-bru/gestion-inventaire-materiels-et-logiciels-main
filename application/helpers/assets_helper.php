<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function css_url($nom)
{
    return base_url() . 'assets/css/' . $nom . '.css';
}

function js_url($nom)
{
    return base_url() . 'assets/js/' . $nom . '.js';
}

function img_url($nom)
{
    return base_url() . 'assets/images/' . $nom;
}

function upload_url($nom)
{
    return 'assets/uploads/' . $nom;
}

function img($nom, $alt='')
{
    return '<img src="' . img_url($nom) . '" alt="' . $alt . '">';
}

if ( ! function_exists('lien'))
{
    function lien($nom, $url, $class = '')
    {
        return '<a href="' . site_url($url) . '" class="' . $class . '" >' . $nom . '</a>';
    }
}

function array_add_elm_id($container, $elements) {
    $ids = [];
    foreach ($container as $item)
        $ids[] = $item['id'];
    foreach ($elements as $element) {
        $element = (array)$element;
        if (!in_array($element['id'], $ids))
            $container[] = $element;
    }
    return $container;
}