<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Warning;
use App\Http\Requests\NewWarningRequest;
class WarningsController extends Controller
{
    public function applyFilters($casesTable,$r){
        $advices=[];
  
        if($r->input('close') === "true"){
            $advices[]='Close';
        }
        if($r->input('casual') === "true" ){
            $advices[]='Casual';
        }
        if($r->input('staff') === "true" ){
            $advices[]='Staff';
        }
        if($r->input('customer') === "true" ){
            $advices[]='Customer';
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
      
        return $casesTable;
    }
    public function index (Request $r){
        $casesTable = new Warning;
        $casesTable = $this->applyFilters($casesTable,$r);

        $defaultCase=Warning::orderBy('created_at','desc')->first() ? Warning::orderBy('created_at','desc')->first() :(Object)['data_latitude'=>'','data_longitude'=>''];
        $features = $casesTable->get()->map(function($c) use ($defaultCase){
            return [
                'type'=>'Feature',
                'properties'=>[
                    'id'=>$c->data_id,
                    'lgas'=>$c->lags,
                    'advise'=>$c->data_advice,
                    'state'=>$c->state,
                    'suburb'=>$c->data_suburb,
                    'address'=>$c->data_address,
                    'full_address'=>$c->full_address,
                    'location'=>$c->data_location,
                    'date'=>$c->data_datetext,
                    'time'=>$c->data_timetext,
                    'start_time'=>$c->data_timestart,
                    'end_time'=>$c->data_timeend,
                    'submitted_by'=>$c->data_submitted_by,
                    'positive_case_type'=>$c->data_positive_case_type,
                    'positive_case_date'=>$c->data_positive_case_date,
                    'source'=>$c->data_source,
                    'comments'=>$c->data_comments,
                    'isVisible'=>true,
                    'icon'=>$c->getIcon()
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

        $w                       = new Warning;
        $w->data_date            = $r->input('date');
        $w->data_advice          = $r->input('advice');
        $w->data_location        = $r->input('location');
        $w->data_address         = $r->input('address');
        $w->data_suburb          = $r->input('suburb');
        $w->data_state           = $r->input('state');
        $w->data_timestart       = $r->input('start_time');
        $w->data_timeend         = $r->input('end_time');
        $w->data_datetext        = $r->input('date');
        $w->data_timetext        = $r->input('start_time')."-".$r->input('end_time');
        $w->data_comments        = $r->input('comments');
        $w->data_positive_test_date        = $r->input('positive_case_date');
        $w->data_positive_test_type        = $r->input('positive_case_type');
        $w->data_submitted_by     = $r->input('submitted_by');
        $w->data_email            = $r->input('email');
        $w->data_source            = $r->input('source');
        $w->setCoordinates();
       // dd($w);

        
        $w->save();
        return $w;
    }
  

    public function getSuburbs(Request $r){
        $casesTable=\DB::table('dataqld')->selectRaw('data_suburb,count(data_id) as total')->orderBy('data_suburb');
        $casesTable = $this->applyFilters($casesTable,$r);
        return $casesTable->groupBy('data_suburb')->get()->map(function($m){ return ['total'=>$m->total,'suburb'=>$m->data_suburb];});
    }
    public function getLgs(Request $r){
        $casesTable=\DB::table('dataqld')->selectRaw('data_lgas,count(data_id) as total')->orderBy('data_lgas');
        $casesTable = $this->applyFilters($casesTable,$r);
        return $casesTable->groupBy('data_lgas')->get()->map(function($m){ return ['total'=>$m->total,'name'=>$m->data_lgas];});
    }
    public function getDays(Request $r){
        $casesTable= \DB::table('dataqld')->selectRaw('data_date,count(data_id) as total')->orderBy('data_date');
        $casesTable = $this->applyFilters($casesTable,$r);
        return $casesTable->groupBy('data_date')->get()->map(function($m){ return ['total'=>$m->total,'name'=>$m->data_date];});
    }
}
