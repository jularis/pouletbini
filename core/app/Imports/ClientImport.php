<?php

namespace App\Imports;
 
use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 
use Maatwebsite\Excel\Concerns\WithValidation; 

class ClientImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function rules(): array
    {
        return[
            'nom' => 'required',  
            'telephone' => 'required',  
        ];
    }
    public function collection(Collection $collection)
    {
        
        $j=0;
        $k=0;
        if(count($collection)){
 
        foreach($collection as $row)
         { 

             
            $client = new Client(); 
          
            $client->name = $row['nom']; 
            $client->genre = $row['sexe']; 
            $client->phone = $row['telephone'];
            $client->email = $row['email'];
            $client->address = $row['adresse']; 
            $client->status = 1; 
           
            $client->save(); 
          $j++;  

    }

    if(!empty($j))
    {
     $notify[] = ['success',"$j Clients ont été crée avec succès."];
     if(!empty($k)){
         $notify[] = ['error',"$k Client(s) n'ont pas été ajoutés à la base car ils existent déjà."];
     }
      return redirect()->route('admin.livraison.categorie.client.index')->withNotify($notify); 
    }else{
        $notify[] = ['error',"Aucun Client n'a été ajouté à la base car ils existent déjà."];
      return redirect()->route('admin.livraison.categorie.client.index')->withNotify($notify); 
   } 
}else{
    
    $notify[] = ['error',"Il n'y a aucune données dans le fichier."];
      return redirect()->route('admin.livraison.categorie.client.index')->withNotify($notify); 
}

    }

   
    
}
