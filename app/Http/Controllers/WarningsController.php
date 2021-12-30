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
            $casesTable = $casesTable->whereIn('advice',$advices);
        }
        if($r->input('time_start_hour')){
            $casesTable = $casesTable->where('start_hour','>=',$r->input('time_start_hour'));
        }
        if($r->input('time_finish_hour')){
            $casesTable = $casesTable->where('finish_hour','<=',$r->input('time_finish_hour'));
        }
        if($r->input('suburb')){
            $casesTable = $casesTable->where('suburb',$r->input('suburb'));
        }
        $defaultCase=$casesTable->clone()->first() ? $casesTable->clone()->first() :(Object)['lat'=>'','lng'=>''];
        $features = $casesTable->get()->map(function($c) use ($defaultCase){
            return [
                'type'=>'Feature',
                'properties'=>[
                    'id'=>$c->id,
                    'lgas'=>$c->lgas,
                    'advise'=>$c->advice,
                    'suburb'=>$c->suburb,
                    'address'=>$c->address,
                    'date'=>$c->datetext,
                    'time'=>$c->timetext,
                    'start_hour'=>$c->start_hour,
                    'start_hour'=>$c->finish_hour,
                    'isVisible'=>true,
                    'icon'=>$c->advice == 'Close' ? '/img/red_pointer.png':'/img/yellow_pointer.png',
                ],
                'geometry'=>[
                    'type'=>'Point',
                    'coordinates'=>[  (Double) $c->lng ,(Double) $c->lat ]
                ]
            ];
        });
      
        return [
            'type'=>'FeatureCollection',
            'name'=>'point',
            'crs'=>['type'=>'name','properties'=>['name'=>'urn:ogc:def:crs:OGC:1.3:CRS84','lat'=>(Double)$defaultCase->lat,'lng'=>(Double) $defaultCase->lng]],
            'features'=>$features
        ];
    }
   
    public function store(NewWarningRequest $r){
        $fakeEntry          = \Carbon\Carbon::now();
        $fakeExit          = \Carbon\Carbon::now()->addMinutes(rand(20,60));

        $w                  = new Warning;
        $w->address         = $r->input('address');
        $w->suburb          = $r->input('suburb');
        $w->lat             = $r->input('lat');
        $w->lng             = $r->input('lng');
        $w->advice          = $r->input('advice');
        $w->datetext        = $fakeEntry->format('l d F Y');
     
        $w->timetext        = $fakeEntry->format('g:i A')."-".$fakeExit->format('g:i A');
        $w->start_hour      = $fakeEntry->format('H');
        $w->finish_hour     = $fakeExit->format('H');

        $w->save();
        return $w;
    }
  

    public function getSuburbs(){
        return \DB::table('warnings')->selectRaw('suburb,count(id) as total')->orderBy('suburb')->groupBy('suburb')->get()->map(function($m){ return ['total'=>$m->total,'suburb'=>$m->suburb];});
    }
}
