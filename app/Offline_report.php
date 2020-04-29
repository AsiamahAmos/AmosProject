<?php

namespace App;
use Schema;
use Illuminate\Database\Eloquent\Model;

class Offline_report extends Model
{
    //u can also state the name of the table the model is refering to over here in case the eloquent statement is not recognising the exact table.
   public $table = "offline_report";
}
