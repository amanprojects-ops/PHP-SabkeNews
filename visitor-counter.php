<?php 
//visitor logs generator any pages 
// Get visitor's IP address
$ip = $_SERVER['REMOTE_ADDR'];
$public_ip = file_get_contents('https://api.ipify.org');

// Get MAC address (Note: May not work in all environments)
$mac = false;
$arp = `arp -a $ip`;
$lines = explode("\n", $arp);
foreach($lines as $line) {
    if (strpos($line, $ip) !== false) {
        $mac = substr($line, strpos($line, "at ") + 3, 17);
        break;
    }
}

// Get browser details
$user_agent = $_SERVER['HTTP_USER_AGENT'];
// Get referrer URL
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Visit';

// Get timezone and time
$timezone = date_default_timezone_get();
$current_time = date('Y-m-d H:i:s');

// Get location using IP geolocation
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
$country = $geo['geoplugin_countryName'];
$city = $geo['geoplugin_city'];
$region = $geo['geoplugin_region'];

// Get Public IP address Details
$public_ip_details = json_decode(file_get_contents("http://ip-api.com/json/$public_ip"), true);
$public_ip_city = $public_ip_details['city'];
$public_ip_region = $public_ip_details['regionName'];
$public_ip_country = $public_ip_details['country'];
$public_ip_timezone = $public_ip_details['timezone'];
$public_ip_latitude = $public_ip_details['lat'];
$public_ip_longitude = $public_ip_details['lon'];
$public_ip_isp = $public_ip_details['isp'];
$public_ip_org = $public_ip_details['org'];
$public_ip_as = $public_ip_details['as'];
$public_ip_query = $public_ip_details['query'];
$public_ip_status = $public_ip_details['status'];
$public_ip_country_code = $public_ip_details['countryCode'];
$public_ip_region_name = $public_ip_details['regionName'];  
$public_ip_city_name = $public_ip_details['city'];
$public_ip_zip = $public_ip_details['zip'];


// Create log entry
$log_entry = array(
    'timestamp' => $current_time,
    'ip_address' => $ip,
    'public_ip' => $public_ip,
    'mac_address' => $mac,
    'browser' => $user_agent,
    'referrer' => $referrer,
    'location' => "$city, $region, $country",
    'timezone' => $timezone,
    'public_ip_details' => array(
        'city' => $public_ip_city,
        'region' => $public_ip_region,
        'country' => $public_ip_country,
        'timezone' => $public_ip_timezone,
        'latitude' => $public_ip_latitude,
        'longitude' => $public_ip_longitude,
        'isp' => $public_ip_isp,
        'org' => $public_ip_org,
        'as' => $public_ip_as,
        'query' => $public_ip_query,
        'status' => $public_ip_status,
        'country_code' => $public_ip_country_code,
        'region_name' => $public_ip_region_name,  
        'city_name' => $public_ip_city_name,
        'zip' => $public_ip_zip
    )
);

// Save to database or log file
$log_file = 'visitor_logs.txt'; // Change this to your desired log file path
// Check if the file exists, if not create it
file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND);

?>