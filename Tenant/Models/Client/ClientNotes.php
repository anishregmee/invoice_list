<?php namespace App\Modules\Tenant\Models\Client;
use App\Modules\Tenant\Models\Notes;
use Illuminate\Database\Eloquent\Model;
use DB;


class ClientNotes extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'client_notes';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['note_id', 'client_id'];

    public $timestamps = false;

     public function notes()
    {
        return $this->belongsTo('App\Modules\Tenant\Models\Notes');
    }


    public function getClientNotes($client_id)
    {
    $client_notes = Notes::orderBy('notes_id', 'desc')->get();
        return $client_notes;
    }

    public function uploadClientNotes($client_id, array $request)
    {
        $notes = Notes::create([  
                'added_by_user_id' => current_tenant_id(),      
                'description' => $request['description'],                
                'remind' => (isset($request['remind']))? 1 : 0,              
                'reminder_date' => $request['reminder_date'],
            ]);

        $client_notes = ClientNotes::create([  
                'note_id' => $notes->notes_id,      
                'client_id' => $client_id
            ]);
    }

   public function deleteNote($note_id)
    {
          DB::beginTransaction();

        try {
        Notes::destroy($note_id);
        $client_notes = ClientNotes::where('note_id', $note_id)->first()->delete();
       
        DB::commit();
           return true;
          
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
       
    }


}

