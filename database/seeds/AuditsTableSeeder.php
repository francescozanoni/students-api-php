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
            [
                'id' => 2,
                'user_type' => 'App\User',
                'user_id' => 0,
                'event' => 'created',
                'auditable_type' => 'App\Models\EducationlActivityAttendance',
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
                'auditable_type' => 'App\Models\EducationlActivityAttendance',
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
                'auditable_type' => 'App\Models\EducationlActivityAttendance',
                'auditable_id' => 2,
                'old_values' => '{"educational_activity":"Second educational activity","start_date":"2019-01-03","credits":1.0,"student_id":1,"id":2}',
                'new_values' => '[]',
                'url' => 'http://localhost/educational_activity_attendances/2',
                'ip_address' => '::1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:53.0) Gecko/20100101 Firefox/53.0',
                'tags' => null,
                'created_at' => '2019-01-06 01:00:00',
                'updated_at' => '2019-01-06 01:00:00',
            ]
        );
    }
}


