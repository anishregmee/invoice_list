<?php
namespace App\Modules\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Modules\Tenant\Models\Application\CourseApplication;

Class Note extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notes';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'notes_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['description','added_by_user_id'];

    /**
     * Disable default timestamp
     *
     * @var boolean
     */
    public $timestamps = false;

       
        function note_create(array $request)
        //function note_create(array $request , $notes_id)
     
      {
          DB::beginTransaction();

          try {

              $notes = Note::create([
                'description'      => $request['notes'],
                'added_by_user_id' => current_tenant_id()
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