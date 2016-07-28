<?php namespace App\Modules\Tenant\Models\Institute;

use App\Modules\Tenant\Models\Company\Company;
use App\Modules\Tenant\Models\Company\CompanyContact;
use App\Modules\Tenant\Models\Person\PersonPhone;
use App\Modules\Tenant\Models\Phone;
use App\Modules\Tenant\Models\User;
use App\Modules\Tenant\Models\Address;
use App\Modules\Tenant\Models\Person\Person;
use App\Modules\Tenant\Models\Person\PersonAddress;
use Illuminate\Database\Eloquent\Model;
use DB;

class Institute extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'institutes';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'institution_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['name', 'added_by', 'short_name', 'company_id'];

    /**
     * Get the company record associated with the institute.
     */
    public function company()
    {
        return $this->belongsTo('App\Modules\Tenant\Models\Company\Company');
    }


    /*
     * Add institute info
     * Output institute id
     */
    function add(array $request)
    {
        DB::beginTransaction();

        try {
            // Saving phone number
            $phone = new Phone();
            $phone_id = $phone->add($request['phone']);

            // Saving company
            $company = Company::create([
                'name' => $request['name'],
                'phone_id' => $phone_id,
                'website' => $request['website'],
                'invoice_to_name' => $request['invoice_to_name']
            ]);

            $institute = Institute::create([
                'short_name' => $request['short_name'],
                'company_id' => $company->company_id,
                'added_by'=> current_tenant_id()
            ]);

            
            $address = Address::create([
                'street' => $request['street'],
                'suburb' => $request['suburb'],
                'postcode' => $request['postcode'],
                'state' => $request['state'],
                'country_id' => $request['country_id']
            ]);

            $instituteAddress=InstituteAddress::create([
                'address_id' => $address->address_id,
                'institute_id' => $institute->institution_id
            ]);

            DB::commit();
            return $institute->institution_id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            // something went wrong
        }
    }

    /*
     * Update institute info
     * Output boolean
     */
    function edit(array $request, $institution_id)
    {
        DB::beginTransaction();

        try {

            $institute = Institute::find($institution_id);
            // Saving institute profile
            $person = Person::find($institute->person_id);
            $person->first_name = $request['first_name'];
            $person->middle_name = $request['middle_name'];
            $person->last_name = $request['last_name'];
            $person->dob = insert_dateformat($request['dob']);
            $person->sex = $request['sex'];
            $person->passport_no = $request['passport_no'];
            $person->save();

            $user = User::find($institute->user_id);
            $user->email = $request['email'];
            $user->save();

            $person_address = PersonAddress::where('person_id', $institute->person_id)->first();
            $address = Address::find($person_address->address_id);

            // Edit address
            $address->street = $request['street'];
            $address->suburb = $request['suburb'];
            $address->postcode = $request['postcode'];
            $address->state = $request['state'];
            $address->country_id = $request['country_id'];
            $address->save();

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

    /*
     * Get institute info
     * Output Response
     */
    function getDetails($institution_id)
    {
        $institute = Institute::leftJoin('companies', 'institutes.company_id', '=', 'companies.company_id')
            ->leftJoin('phones', 'phones.phone_id', '=', 'companies.phone_id')
            ->leftJoin('users', 'users.user_id', '=', 'institutes.added_by')
            ->select(['institutes.institution_id', 'institutes.short_name', 'institutes.created_at','users.email', 'companies.name', 'companies.website', 'companies.invoice_to_name', 'phones.number'])
            ->where('institutes.institution_id', $institution_id)
            ->first();
        return $institute;
    }

    /*
     * Add contact person
     * Output id
     */
    function addContact($institution_id, array $request)
    {
        DB::beginTransaction();

        try {
            $institute = Institute::find($institution_id);
            $company_id = $institute->company_id;

            $person_id = $this->addPersonDetails($request);

            CompanyContact::create([
                'company_id' => $company_id,
                'person_id' => $person_id,
                'position' => $request['position']
            ]);
            DB::commit();
            return true;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /*
     * Add address person
     * Output id
     */
    function addAddress($institution_id, array $request)
    {
        DB::beginTransaction();

        try {
            $address = Address::create([
                'street' => $request['street'],
                'suburb' => $request['suburb'],
                'state' => $request['state'],
                'country_id' => 263, //Australia
                'type' => 'office'
            ]);
           
           
            $instituteAddress=InstituteAddress::create([
                'address_id' => $address->address_id,
                'institute_id' => $institution_id,
                'email' => $request['email']
            ]);
            
            
            $phone = new Phone();
            $phone_id = $phone->add($request['number']);
        
            InstitutePhone::create([
                'address_id' => $address->address_id,
                'phone_id' => $phone_id
            ]);
            

            DB::commit();
            
           
            return true;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    function addPersonDetails($request)
    {
        // Saving contact profile
        $person = Person::create([
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'sex' => $request['sex']
        ]);

        User::create([
            'email' => $request['email'],
            'user_type' => 3, // 0 : client, 1 : admin, 2 : super-admin, 3 : contact person
            'status' => 0, // Pending
            'person_id' => $person->person_id, // pending
        ]);

        // Add Phone Number
        $phone = new Phone();
        $phone_id = $phone->add($request['number']);
        PersonPhone::create([
            'phone_id' => $phone_id,
            'person_id' => $person->person_id,
            'is_primary' => 1
        ]);

        return $person->person_id;
    }

    /*
     * Get institutes list
     * Output Response
     */
    function getList()
    {
        $institutes = Institute::leftJoin('companies', 'institutes.company_id', '=', 'companies.company_id')
            ->leftJoin('phones', 'phones.phone_id', '=', 'companies.phone_id')
            ->lists('companies.name', 'institutes.institution_id');
        return $institutes;
    }
}
