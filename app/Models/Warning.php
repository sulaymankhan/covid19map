<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
class Warning extends Model{
    protected $table='dataqld';
    protected $appends=['full_address'];
    public static function generateFakeGls(){
        $lgs=['QLD142','QLD143','QLD144','QLD145','QLD146','QLD147','QLD148','QLD149','QLD145'];
        $warnings = Warning::get();
        foreach($warnings as $w):
            $w->lgs = $lgs[rand(0,8)];
            $w->save();
        endforeach;
    }

    public static function getCoordinates(String $address){
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=".config('app.GOOGLE_API_KEY');
        $response = Http::get($url);
        if($response->status()==200){
            $response= $response->object();
            $lat = isset($response->results[0]->geometry->location->lat) ? $response->results[0]->geometry->location->lat : false;
            $lng = isset($response->results[0]->geometry->location->lng) ? $response->results[0]->geometry->location->lng : false;
            if(!$lat){
                return false;
            }
            return (Object) ['lat'=>$lat,'lng'=>$lng];
        }
       return false;
    }

    public function getFullAddressAttribute(){
        return $this->data_location." ".$this->data_address." ".$this->data_suburb." ".$this->data_state." Australia";
    }

    public function setCoordinates(){
        $coords = Warning::getCoordinates($this->full_address);
        if($coords){
            $this->data_latitude     = $coords->lat;
            $this->data_longitude    = $coords->lng;
            $this->save();
        }
       
    }

    public function getIcon(){
        switch($this->data_advice){
            case 'Close':
                return '/img/red_pointer.png';
                break;
            case 'Casual':
                return '/img/yellow_pointer.png';
                break;

            case 'Customer':
                return '/img/blue_pointer.png';
                break;

            case 'Staff':
                return '/img/green_pointer.png';
                break;
        }
        return $icon;
    }

}