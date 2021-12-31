<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model{
    public static function generateFakeGls(){
        $lgs=['QLD142','QLD143','QLD144','QLD145','QLD146','QLD147','QLD148','QLD149','QLD145'];
        $warnings = Warning::get();
        foreach($warnings as $w):
            $w->lgs = $lgs[rand(0,8)];
            $w->save();
        endforeach;
    }
}