<?php

namespace App\Exports;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;  

class ExportCommandes implements WithMultipleSheets
{
     
      public function sheets(): array 
    {    
      $feuilles=array();  
      if(!request()->staff)
      {
          if(request()->magasin){
              $user = User::where('magasin_id', request()->magasin)->get();
          }else{
              $user = User::get();
          }
         
      }else{
          $user = User::where('id', request()->staff)->get();
      }
      foreach($user as $data){
      $feuilles[] = new UsersCommandesExport($data->id);
      }
    $sheets = [ new Commandes(), ];
    $sheets = array_merge($sheets, $feuilles);
   

    return $sheets; 
    }

   
}
