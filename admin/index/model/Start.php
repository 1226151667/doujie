<?php
namespace app\index\model;
use think\Model;
class Start extends Model{
    public function set_password($p){
        $num_rep = array(
        	array(0,1,2,3,4,5,6,7,8,9),
			array(9,8,7,6,5,4,3,2,1,0)
		);
        $letter_rep = array(
        	array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'),
        	array('e','d','c','b','a','j','i','h','g','f','o','n','m','l','k','r','q','p','t','s','v','u','z','y','x','w')
        );
        $p =  md5(str_replace($num_rep[0], $num_rep[1], $p));
        return md5(str_replace($letter_rep[0], $letter_rep[1], $p));
    }
    public function set_address($ip){
        $str = file_get_contents("http://api.map.baidu.com/location/ip?ak=P5DNCRqHRTkqpH47PzrteNcuaj4qwyRD&ip={$ip}");
        $jsonData = json_decode($str);
        $data=$jsonData->content->address_detail;
        $pro = $data->province;
        $city = $data->city;
        $address = "{$pro}-{$city}";
        if($data->street && $data->street_number){
            $address+="-{$street} {$street_number}";
        }
        return $address;
    }
}