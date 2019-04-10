<?php
declare(strict_types = 1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('audits')->insert(

            // Annotations
            [
                'id' => 1,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Annotation',
                'auditable_id' => 1,
                'old_values' => '[]',
                'new_values' => '{"title":"First title","content":"First content","student_id":1,"id":1}',
                'url' => 'http://localhost/students/1/annotations',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-01 01:00:00',
                'updated_at' => '2019-01-01 01:00:00',
            ],

            // Educational activity attendances
            [
                'id' => 2,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\EducationalActivityAttendance',
                'auditable_id' => 1,
                'old_values' => '[]',
                'new_values' => '{"educational_activity":"First educational activity","start_date":"2019-01-08","end_date":"2019-01-09","credits":1.2,"student_id":1,"id":1}',
                'url' => 'http://localhost/students/1/educational_activity_attendances',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-10 01:00:00',
                'updated_at' => '2019-01-10 01:00:00',
            ],
            [
                'id' => 3,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\EducationalActivityAttendance',
                'auditable_id' => 2,
                'old_values' => '[]',
                'new_values' => '{"educational_activity":"Second educational activity","start_date":"2019-01-03","credits":1.0,"student_id":1,"id":2}',
                'url' => 'http://localhost/students/1/educational_activity_attendances',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-05 01:00:00',
                'updated_at' => '2019-01-05 01:00:00',
            ],
            [
                'id' => 4,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'deleted',
                'auditable_type' => 'App\Models\EducationalActivityAttendance',
                'auditable_id' => 2,
                'old_values' => '{"educational_activity":"Second educational activity","start_date":"2019-01-03","credits":1.0,"student_id":1,"id":2}',
                'new_values' => '[]',
                'url' => 'http://localhost/educational_activity_attendances/2',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-06 01:00:00',
                'updated_at' => '2019-01-06 01:00:00',
            ],

            // Eligibilities
            [
                'id' => 5,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Eligibility',
                'auditable_id' => 1,
                'old_values' => '[]',
                'new_values' => '{"start_date":"2019-01-01","end_date":"2019-12-01","notes":"Zero eligibility notes","is_eligible":false,"student_id":1,"id":1}',
                'url' => 'http://localhost/students/1/eligibilities',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-02 01:00:00',
                'updated_at' => '2019-01-02 01:00:00',
            ],
            [
                'id' => 6,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'deleted',
                'auditable_type' => 'App\Models\Eligibility',
                'auditable_id' => 1,
                'old_values' => '{"start_date":"2019-01-01","end_date":"2019-12-01","notes":"Zero eligibility notes","is_eligible":false,"student_id":1,"id":1}',
                'new_values' => '[]',
                'url' => 'http://localhost/eligibilities/1',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-02 01:10:00',
                'updated_at' => '2019-01-02 01:10:00',
            ],
            [
                'id' => 7,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Eligibility',
                'auditable_id' => 2,
                'old_values' => '[]',
                'new_values' => '{"start_date":"2019-01-01","end_date":"2019-12-01","notes":"First eligibility notes","is_eligible":true,"student_id":1,"id":2}',
                'url' => 'http://localhost/students/1/eligibilities',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-02 02:00:00',
                'updated_at' => '2019-01-02 02:00:00',
            ],
            [
                'id' => 8,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Eligibility',
                'auditable_id' => 3,
                'old_values' => '[]',
                'new_values' => '{"start_date":"2019-01-01","end_date":"2019-12-01","is_eligible":false,"student_id":2,"id":3}',
                'url' => 'http://localhost/students/2/eligibilities',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-02 03:00:00',
                'updated_at' => '2019-01-02 03:00:00',
            ],

            // Evaluations
            [
                'id' => 9,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Evaluation',
                'auditable_id' => 1,
                'old_values' => '[]',
                'new_values' => '{"notes":"First evaluation notes","item_1":"A","item_2":"B","internship_id":1,"id":1}',
                'url' => 'http://localhost/internships/1/evaluations',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-25 02:00:00',
                'updated_at' => '2019-01-25 02:00:00',
            ],
            [
                'id' => 10,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Evaluation',
                'auditable_id' => 2,
                'old_values' => '[]',
                'new_values' => '{"notes":"Second evaluation notes","item_1":"A","item_2":"B","internship_id":1,"id":2}',
                'url' => 'http://localhost/internships/1/evaluations',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-26 02:00:00',
                'updated_at' => '2019-01-26 02:00:00',
            ],
            [
                'id' => 11,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'deleted',
                'auditable_type' => 'App\Models\Evaluation',
                'auditable_id' => 2,
                'old_values' => '{"notes":"Second evaluation notes","item_1":"A","item_2":"B","internship_id":1,"id":2}',
                'new_values' => '[]',
                'url' => 'http://localhost/evaluations/2',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-26 03:00:00',
                'updated_at' => '2019-01-26 03:00:00',
            ],

            // Internships
            [
                'id' => 12,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Internship',
                'auditable_id' => 1,
                'old_values' => '[]',
                'new_values' => '{"student_id":1,"location_id":1,"sub_location_id":1,"start_date":"2019-01-10","end_date":"2019-01-24","hour_amount":123,"other_amount":5,"is_optional":false,"is_interrupted":false,"id":1}',
                'url' => 'http://localhost/students/1/internships',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-09 02:00:00',
                'updated_at' => '2019-01-09 02:00:00',
            ],
            [
                'id' => 13,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Internship',
                'auditable_id' => 2,
                'old_values' => '[]',
                'new_values' => '{"student_id":1,"location_id":1,"sub_location_id":1,"start_date":"2019-01-26","end_date":"2019-01-31","hour_amount":34,"other_amount":0,"is_optional":true,"is_interrupted":true,"id":2}',
                'url' => 'http://localhost/students/1/internships',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-25 02:00:00',
                'updated_at' => '2019-01-25 02:00:00',
            ],
            [
                'id' => 14,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Internship',
                'auditable_id' => 3,
                'old_values' => '[]',
                'new_values' => '{"student_id":4,"location_id":1,"sub_location_id":1,"start_date":"2019-01-26","end_date":"2019-01-31","hour_amount":34,"other_amount":0,"is_optional":false,"is_interrupted":false,"id":3}',
                'url' => 'http://localhost/students/4/internships',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-25 02:00:00',
                'updated_at' => '2019-01-25 02:00:00',
            ],
            [
                'id' => 15,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'updated',
                'auditable_type' => 'App\Models\Internship',
                'auditable_id' => 3,
                'old_values' => '{"student_id":4,"location_id":1,"sub_location_id":1,"start_date":"2019-01-26","end_date":"2019-01-31","hour_amount":34,"other_amount":0,"is_optional":false,"is_interrupted":false,"id":3}',
                'new_values' => '{"student_id":4,"location_id":1,"sub_location_id":1,"start_date":"2019-01-26","end_date":"2019-01-31","hour_amount":34,"other_amount":0,"is_optional":false,"is_interrupted":true,"id":3}',
                'url' => 'http://localhost/internships/3',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-28 02:00:00',
                'updated_at' => '2019-01-28 02:00:00',
            ],
            [
                'id' => 16,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\Internship',
                'auditable_id' => 4,
                'old_values' => '[]',
                'new_values' => '{"student_id":4,"location_id":1,"sub_location_id":1,"start_date":"' . (new DateTime())->add(new DateInterval('P10D'))->format('Y-m-d') . '","end_date":"' . (new DateTime())->add(new DateInterval('P20D'))->format('Y-m-d') . '","hour_amount":0,"other_amount":0,"is_optional":false,"is_interrupted":false,"id":4}',
                'url' => 'http://localhost/students/4/internships',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-25 02:00:00',
                'updated_at' => '2019-01-25 02:00:00',
            ],
            [
                'id' => 17,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'updated',
                'auditable_type' => 'App\Models\Internship',
                'auditable_id' => 4,
                'old_values' => '{"student_id":4,"location_id":1,"sub_location_id":1,"start_date":"' . (new DateTime())->add(new DateInterval('P10D'))->format('Y-m-d') . '","end_date":"' . (new DateTime())->add(new DateInterval('P20D'))->format('Y-m-d') . '","hour_amount":0,"other_amount":0,"is_optional":false,"is_interrupted":false,"id":4}',
                'new_values' => '{"student_id":4,"location_id":1,"sub_location_id":1,"start_date":"' . (new DateTime())->add(new DateInterval('P10D'))->format('Y-m-d') . '","end_date":"' . (new DateTime())->add(new DateInterval('P20D'))->format('Y-m-d') . '","hour_amount":0,"other_amount":0,"is_optional":false,"is_interrupted":true,"id":4}',
                'url' => 'http://localhost/internships/4',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-28 02:00:00',
                'updated_at' => '2019-01-28 02:00:00',
            ],

            // Interruption reports
            [
                'id' => 18,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\InterruptionReport',
                'auditable_id' => 1,
                'old_values' => '[]',
                'new_values' => '{"notes":"First interruption report notes","internship_id":2,"id":1}',
                'url' => 'http://localhost/internships/2/interruption_reports',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-30 02:00:00',
                'updated_at' => '2019-01-30 02:00:00',
            ],
            [
                'id' => 19,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'deleted',
                'auditable_type' => 'App\Models\InterruptionReport',
                'auditable_id' => 1,
                'old_values' => '{"notes":"First interruption report notes","internship_id":2,"id":1}',
                'new_values' => '[]',
                'url' => 'http://localhost/interruption_reports/1',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-30 03:00:00',
                'updated_at' => '2019-01-30 03:00:00',
            ],
            [
                'id' => 20,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\InterruptionReport',
                'auditable_id' => 2,
                'old_values' => '[]',
                'new_values' => '{"notes":"Second interruption report notes","internship_id":2,"id":2}',
                'url' => 'http://localhost/internships/2/interruption_reports',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-31 02:00:00',
                'updated_at' => '2019-01-31 02:00:00',
            ],

            // Occupational safety and health course attendances
            [
                'id' => 21,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\OshCourseAttendance',
                'auditable_id' => 1,
                'old_values' => '[]',
                'new_values' => '{"start_date":"2018-12-08","end_date":"2019-12-07","student_id":1,"id":1}',
                'url' => 'http://localhost/students/1/osh_course_attendances',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2018-12-10 01:00:00',
                'updated_at' => '2018-12-10 01:00:00',
            ],
            [
                'id' => 22,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\OshCourseAttendance',
                'auditable_id' => 2,
                'old_values' => '[]',
                'new_values' => '{"start_date":"2018-12-08","end_date":"2019-12-07","student_id":4,"id":2}',
                'url' => 'http://localhost/students/4/osh_course_attendances',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2018-12-10 01:00:00',
                'updated_at' => '2018-12-10 01:00:00',
            ],
            [
                'id' => 23,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'deleted',
                'auditable_type' => 'App\Models\OshCourseAttendance',
                'auditable_id' => 2,
                'old_values' => '{"start_date":"2018-12-08","end_date":"2019-12-07","student_id":4,"id":2}',
                'new_values' => '[]',
                'url' => 'http://localhost/osh_course_attendances/2',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2018-12-11 04:00:00',
                'updated_at' => '2018-12-11 04:00:00',
            ],
            [
                'id' => 24,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\OshCourseAttendance',
                'auditable_id' => 3,
                'old_values' => '[]',
                'new_values' => '{"start_date":"2018-12-14","end_date":"2019-12-13","student_id":4,"id":3}',
                'url' => 'http://localhost/students/4/osh_course_attendances',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2018-12-15 01:00:00',
                'updated_at' => '2018-12-15 01:00:00',
            ]

            // @todo re-set IDs to match dates/times sequence
        );
    }
}


