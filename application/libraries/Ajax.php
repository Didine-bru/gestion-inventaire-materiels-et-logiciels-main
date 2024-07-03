<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Ajax
{
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
    }

    /**
     * Output
     *
     * @param mixed $data The data to encode and output.
     * @param string $output_type The output encode type. Options 'json', 'xml', 'html', or 'text'. Defaults to 'json'.
     * @param boolean $ajax_only Reject if this is not called by an xmlhttprequest. Defaults to TRUE.
     */
    function output($data = NULL, $status = 200, $message="", $output_type = 'json')
    {
        $_output = NULL;

        // Encode data and set headers
        $data = array("data" => $data, "message" => $message);
        switch ($output_type) {
            case 'json':
            default:
                $_output = json_encode($data);
                $this->CI->output->set_header('Content-Type: application/json');
                break;

            case 'xml':
                $this->CI->load->helper('encode_xml');
                $_output = array_to_xml($data);
                $this->CI->output->set_header('Content-Type: application/xhtml+xml');
                break;

            case 'text':
                $_output = $data;
                $this->CI->output->set_header('Content-Type: text/plain');
                break;

            case 'html':
                $_output = $data;
                $this->CI->output->set_header('Content-Type: text/html');
                break;
        }

        $this->CI->output->set_status_header($status);
        $this->CI->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->CI->output->set_header('Pragma: no-cache');
        $this->CI->output->set_header('Access-Control-Allow-Origin: ' . base_url());
        $this->CI->output->set_header('Content-Length: ' . strlen($_output));
        $this->CI->output->set_output($_output);
    }
}
