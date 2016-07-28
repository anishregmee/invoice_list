<?php namespace App\Modules\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Document extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'document_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'user_id', 'name', 'shelf_location', 'description'];

     function document_create(array $request)
         //function note_create(array $request , $notes_id)
     
       {
           DB::beginTransaction();

           try {

                 $file = $request['upload_offer_letter'];
                 $name = $file->getClientOriginalName();
                 $type = $file->getClientMimeType();
                 //$type = $file->guessClientExtension();
                 //$shelf_location = $file->getRealPath();$path = app_path('Http/Controllers/Controller.php');
                 $shelf_location = app_path('modules/Tenant/views/Application/upload');
                 $file->move($shelf_location, $name);

                 $document = Document::create([
                  'type' => $type,
                  'user_id' => current_tenant_id(),
                  'name' => $name,
                  'shelf_location' => $shelf_location,
                  'description' => 'test76'
              ]);
            
           
           

               DB::commit();
               return true;
              // all good
           } catch (\Exception $e) {
               DB::rollback();
               //return false;
               dd($e);
               // something went wrong
           }
       }



}
