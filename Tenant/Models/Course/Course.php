<?php namespace App\Modules\Tenant\Models\Course;

use App\Modules\Tenant\Models\Fee;
use App\Modules\Tenant\Models\Institute\InstituteCourse;
use Illuminate\Database\Eloquent\Model;
use DB;

class Course extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'course_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'broad_field', 'level', 'narrow_field','commission_percent','coe_fee','total_tuition_fee'];

    /**
     * Disable default timestamp feature.
     *
     * @var boolean
     */
    public $timestamps = false;



    /*
     * Add institute info
     * Output course id
     */
    function add(array $request, $institute_id)
    {
        DB::beginTransaction();

        try {
            $course = Course::create([
                'name' => $request['name'],
                'broad_field' => $request['broad_field'],
                'level' => $request['level'],
                'narrow_field' => $request['narrow_field'],
                //'commission_percent' => $request['commission_percent'],
            ]);

            InstituteCourse::create([
                'course_id' => $course->course_id,
                'institute_id' => $institute_id,
                'description' => $request['description'],
            ]);

            $fee = Fee::create([
                'total_tuition_fee' => $request['total_tuition_fee'],
                'coe_fee' => $request['coe_fee'],
            ]);

            CourseFee::create([
                'fees_id' => $fee->fee_id,
                'course_id' => $course->course_id
            ]);

            DB::commit();
            return $course->course_id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            // something went wrong
        }
    }
    

    function getCourses($institute_id)
    {
        $courses = InstituteCourse::join('courses', 'institute_courses.course_id', '=', 'courses.course_id')
            ->where('institute_courses.institute_id', $institute_id)
            ->lists('courses.name', 'courses.course_id');
        return $courses;
    }
}
