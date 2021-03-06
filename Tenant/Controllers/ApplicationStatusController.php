<?php

namespace App\Modules\Tenant\Controllers;

use Illuminate\Http\Request;
use App\Modules\Tenant\Models\Person\Person;
use App\Modules\Tenant\Models\Application\CourseApplication;
use App\Modules\Tenant\Models\Application\ApplicationStatus;
use App\Modules\Tenant\Models\Person\PersonPhone;
use App\Modules\Tenant\Models\User;
use App\Modules\Tenant\Models\Note;
use App\Modules\Tenant\Models\Document;
use Session;
use DB;
use App\Modules\Tenant\Models\Institute_intake;
use App\Modules\Tenant\Models\intake\intake;

class ApplicationStatusController extends BaseController
{
    function __construct(CourseApplication $course_application, Request $request, Note $note, ApplicationStatus $application_status, Document $document)
    {
        $this->course_application = $course_application;
        $this->application_status = $application_status;
        $this->note = $note;
        $this->document = $document;
        $this->request = $request;
        parent::__construct();
    }
    
    //Information for Enquiry page
    public function index()
    {
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id', 'phones.number','users.email'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->get();
        
        return view('Tenant::application/enquiry',['applications'=>$applications]);
    }
    
    //information for action page apply_offer
    public function apply_offer($course_application_id)
    {

        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('institute_intakes', 'institute_intakes.intake_id', '=' , 'course_application.intake_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'institute_intakes.intake_id')
            ->where('course_application.course_application_id',$course_application_id)
            ->select(['persons.first_name','companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id', 'institute_intakes.institute_id','institute_intakes.intake_id'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->find($course_application_id);

        $intakes= Intake::lists('intake_date', 'intake_id')->toArray();

        return view('Tenant::application/action/apply_offer',['applications'=>$applications, 'intakes'=>$intakes]);   
            
    }

     //updates for apply_offer
     public function update($course_application_id)
     {

        $updated = $this->course_application->application_update($this->request->all(), $course_application_id);
            if ($updated)
                $updated = $this->application_status->application_create($this->request->all(), $course_application_id); 
             
        Session::flash('success', 'Updated Successfully');
        return redirect()->route('application.offer_letter_processing.index');
     }


    //Information for cancel/quarantine action page whose parent page is Enquiry
    public function cancel_application($course_application_id)
    {
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->leftjoin('application_notes', 'course_application.course_application_id', '=' , 'application_notes.application_id')
            ->leftjoin('notes', 'application_notes.note_id', '=' , 'notes.notes_id')
            ->where('course_application.course_application_id',$course_application_id)
            ->select(['persons.first_name','companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->find($course_application_id);

        return view('Tenant::application/action/cancel_application',['applications'=>$applications]);   
    }

    //cancel/qurantine actions
    public function cancel_qurantine()
    {
       $created = $this->note->note_create($this->request->all());
        if ($created)
        
        Session::flash('success', 'Quarantinded Successfully');
        return redirect()->route('application.offer_letter_processing.index');
    }

    //Information for offer letter processing page
   public function offerLetterProcessing()
   {
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->join('application_status', 'application_status.course_application_id', '=', 'course_application.course_application_id')
            ->join('status', 'status.status_id', '=', 'application_status.status_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id','application_status.status_id','users.email', 'phones.number'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->get();
        
        return view('Tenant::application/offer_letter_processing',compact('applications'));
    }
    
    //Information for offer_received action page whose parent page is Offer Letter Processing
   public function offer_letter_received($course_application_id)
   {
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->leftjoin('application_notes', 'course_application.course_application_id', '=' , 'application_notes.application_id')
            ->leftjoin('notes', 'application_notes.note_id', '=' , 'notes.notes_id')
            ->leftjoin('application_status', 'application_status.course_application_id', '=' , 'course_application.course_application_id')
            ->leftjoin('application_status_documents', 'application_status_documents.application_status_id', '=' , 'application_status.application_status_id')
            ->leftjoin('documents', 'documents.document_id', '=' , 'application_status_documents.document_id')
            ->where('course_application.course_application_id',$course_application_id)
            ->select(['persons.first_name','companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->find($course_application_id);
    
        return view('Tenant::application/action/offer_letter_received',compact('applications')); 
    }


    //updates for offer_received
    public function offer_received_update($course_application_id)
    {

        $updated = $this->course_application->offer_update($this->request->all(), $course_application_id);
            if ($updated)
                $updated = $this->application_status->offer_create($this->request->all(), $course_application_id);
            
            if ($updated)
                $updated = $this->note->note_create($this->request->all());
            
            if ($updated)
                $updated = $this->document->document_create($this->request->all());

        Session::flash('success', 'Updated Successfully');
        return redirect()->route('application.offer_letter_issued.index');
    }
            
    //information for offer letter issued
   public function offerLetterIssued()
   {
    
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->join('application_status', 'application_status.course_application_id', '=', 'course_application.course_application_id')
            ->join('status', 'status.status_id', '=', 'application_status.status_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id','application_status.status_id','users.email', 'phones.number'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->get();
        
        return view('Tenant::application/offer_letter_issued',compact('applications'));
    }

    public function apply_coe($course_application_id)
   {
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->leftjoin('application_notes', 'course_application.course_application_id', '=' , 'application_notes.application_id')
            ->leftjoin('notes', 'application_notes.note_id', '=' , 'notes.notes_id')
            ->leftjoin('application_status', 'application_status.course_application_id', '=' , 'course_application.course_application_id')
            ->leftjoin('application_status_documents', 'application_status_documents.application_status_id', '=' , 'application_status.application_status_id')
            ->leftjoin('documents', 'documents.document_id', '=' , 'application_status_documents.document_id')
            ->where('course_application.course_application_id',$course_application_id)
            ->select(['intakes.intake_date','course_application.tuition_fee', 'course_application.student_id', 'course_application.course_application_id'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->find($course_application_id);
    
        return view('Tenant::application/action/apply_coe',compact('applications')); 
    }

    //updates for applied_offer
    public function update_applied_coe($course_application_id)
    {
        $updated = $this->course_application->coe_update($this->request->all(), $course_application_id);
            if ($updated)
                $updated = $this->application_status->coe_create($this->request->all(), $course_application_id);

        Session::flash('success', 'Updated Successfully');
        return redirect()->route('application.coe_processing.index');
    }

     //Information for coe processing page
   public function coeProcessing()
   {
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->join('application_status', 'application_status.course_application_id', '=', 'course_application.course_application_id')
            ->join('status', 'status.status_id', '=', 'application_status.status_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id','application_status.status_id','users.email', 'phones.number'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->get();
        
        return view('Tenant::application/coe_processing',compact('applications'));
    }

        //Information for action of coe processing page 
       public function action_coe_issued($course_application_id)
       {
            $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
                ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
                ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
                ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
                ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
                ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
                ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
                ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
                ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
                ->join('application_status', 'application_status.course_application_id', '=', 'course_application.course_application_id')
                ->join('status', 'status.status_id', '=', 'application_status.status_id')
                ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id','application_status.status_id','users.email', 'phones.number'])
                ->orderBy('course_application.course_application_id', 'desc')
                ->find($course_application_id);
            
            return view('Tenant::application/action/coe_issued',compact('applications'));
        }

         //updates for action_coe_issued
    public function update_coe_issued($course_application_id)
    {
        $updated = $this->course_application->coe_issued_update($this->request->all(), $course_application_id);
        if ($updated)
                $updated = $this->document->document_create($this->request->all());

             if ($updated)
                 $updated = $this->application_status->coe_issued_create($this->request->all(), $course_application_id);

        Session::flash('success', 'Updated Successfully');
        return redirect()->route('application.coe_issued.index');
    }



      //Information for coe issued page
   public function coeIssued()
   {
        $applications = CourseApplication::leftjoin('users', 'users.user_id', '=', 'course_application.user_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->leftjoin('institute_courses', 'institute_courses.institute_course_id', '=' , 'course_application.institution_course_id')
            ->leftjoin('courses', 'courses.course_id','=', 'institute_courses.course_id')
            ->leftjoin('institutes', 'institutes.institution_id','=', 'institute_courses.institute_id')
            ->leftjoin('companies', 'companies.company_id','=', 'institutes.company_id')
            ->leftjoin('intakes', 'intakes.intake_id', '=' , 'course_application.intake_id')
            ->join('application_status', 'application_status.course_application_id', '=', 'course_application.course_application_id')
            ->join('status', 'status.status_id', '=', 'application_status.status_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'companies.name as company', 'courses.name', 'intakes.intake_date','course_application.tuition_fee', 'course_application.course_application_id','application_status.status_id','users.email', 'phones.number'])
            ->orderBy('course_application.course_application_id', 'desc')
            ->get();
        
        return view('Tenant::application/coe_issued',compact('applications'));
    }
    


             
              

} //controller ends here