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
            ]

            // @todo re-set IDs to match dates/times sequence
        );
    }
}


