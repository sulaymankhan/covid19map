<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Warning;
use App\Http\Requests\NewWarningRequest;
class WarningsController extends Controller
{
    public function index (Request $r){
        $casesTable = new Warning;
        $advices=[];
        
        if($r->input('close') === "true"){
            $advices[]='Close';
        }
        if($r->input('casual') === "true" ){
            $advices[]='Casual';
        }
        if(count($advices) > 0){
            $casesTable = $casesTable->whereIn('data_advice',$advices);
        }
        if($r->input('time_start_hour') && is_numeric($r->input('time_start_hour'))){
            $casesTable = $casesTable->whereRaw('date_format(data_timestart,"%H")>='.$r->input('time_start_hour'));
        }
        if($r->input('time_finish_hour') && is_numeric($r->input('time_finish_hour'))){
            $casesTable = $casesTable->whereRaw('date_format(data_timeend,"%H")<='.$r->input('time_finish_hour'));
        }
        if($r->input('suburb')){
          
            $casesTable = $casesTable->where('data_suburb',$r->input('suburb'));
        }
        if($r->input('date')){
            $casesTable = $casesTable->where('data_date',$r->input('date'))->orderBy('data_date','desc');
        }
        if($r->input('lgs')){
            $casesTable = $casesTable->where('data_lgas',$r->input('lgs'));
        }
    //    / dd($casesTable->count());
        $defaultCase=$casesTable->clone()->first() ? $casesTable->clone()->first() :(Object)['data_latitude'=>'','data_longitude'=>''];
        $features = $casesTable->get()->map(function($c) use ($defaultCase){
            return [
                'type'=>'Feature',
                'properties'=>[
                    'id'=>$c->data_id,
                    'lgas'=>$c->lags,
                    'advise'=>$c->data_advice,
                    'suburb'=>$c->data_suburb,
                    'address'=>$c->data_address,
                    'date'=>$c->data_datetext,
                    'time'=>$c->data_timetext,
                    'start_hour'=>$c->data_timestart,
                    'start_hour'=>$c->data_timeend,
                    'isVisible'=>true,
                    'icon'=>$c->data_advice == 'Close' ? '/img/red_pointer.png':'/img/yellow_pointer.png',
                ],
                'geometry'=>[
                    'type'=>'Point',
                    'coordinates'=>[   (Double) $c->data_longitude ,(Double) $c->data_latitude ]
                ]
            ];
        });
      
        return [
            'type'=>'FeatureCollection',
            'name'=>'point',
            'crs'=>['type'=>'name','properties'=>['name'=>'urn:ogc:def:crs:OGC:1.3:CRS84','lat'=>(Double)$defaultCase->data_latitude,'lng'=>(Double) $defaultCase->data_longitude]],
            'features'=>$features
        ];
    }
   
    public function store(NewWarningRequest $r){
        $fakeEntry          = \Carbon\Carbon::now();
        $fakeExit          = \Carbon\Carbon::now()->addMinutes(rand(20,60));

        $w                  = new Warning;
        $w->data_address         = $r->input('address');
        $w->data_suburb          = $r->input('suburb');
        $w->data_latitude        = $r->input('lat');
        $w->data_longitude       = $r->input('lng');
        $w->data_advice          = $r->input('advice');
        $w->data_datetext        = $fakeEntry->format('l d F Y');
     
        $w->data_timetext        = $fakeEntry->format('g:i A')."-".$fakeExit->format('g:i A');
        $w->data_timestart       = $fakeEntry->format('H');
        $w->data_timeend         = $fakeExit->format('H');
        
        $w->save();
        return $w;
    }
  

    public function getSuburbs(){
        return \DB::table('dataqld')->selectRaw('data_suburb,count(data_id) as total')->orderBy('data_suburb')->groupBy('data_suburb')->get()->map(function($m){ return ['total'=>$m->total,'suburb'=>$m->data_suburb];});
    }
    public function getLgs(){
        return \DB::table('dataqld')->selectRaw('data_lgas,count(data_id) as total')->orderBy('data_lgas')->groupBy('data_lgas')->get()->map(function($m){ return ['total'=>$m->total,'name'=>$m->data_lgas];});
    }
    public function getDays(){
        return \DB::table('dataqld')->selectRaw('data_date,count(data_id) as total')->orderBy('data_date')->groupBy('data_date')->get()->map(function($m){ return ['total'=>$m->total,'name'=>$m->data_date];});
    }
}
