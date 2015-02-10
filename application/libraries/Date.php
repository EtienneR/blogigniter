<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Date {

    public function get_date_format()
    {
        $date = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $date_format = $date->format('Y-m-d H:i:s');

        return $date_format;
    }

}


/* End of file date.php */