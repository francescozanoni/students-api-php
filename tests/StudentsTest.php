<?php
declare(strict_types = 1);

class StudentsTest extends TestCase
{

    /**
     * Get all students.
     */
    public function testGet()
    {
        $this->json('GET', '/students')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    (new StudentBuilder('john'))->build(),
                    (new StudentBuilder('jane'))->build(),
                    (new StudentBuilder('joan'))->build(),
                ]
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get a student: success.
     */
    public function testGetById()
    {

        $data = (new StudentBuilder('john'))->build();

        // Existing
        $this->json('GET', '/students/' . $data['id'])
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $data
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get a student: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/students/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/students/abc')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400);

    }

    /**
     * Create a student: success.
     */
    public function testCreate()
    {

        $data = (new StudentBuilder('jack'))->build();

        // Valid data
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))->without('id')->build()
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $data
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => $data['id']])
            ->notSeeInDatabase('students', ['id' => $data['id'] + 1]);

    }

    /**
     * Create a student: failure.
     */
    public function testCreateFailure()
    {

        $idNotToFind = (new StudentBuilder('jack'))->build()['id'] + 1;

        // Missing required first_name
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))
                ->without('id')
                ->without('first_name')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'first_name' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => $idNotToFind]);

        // Missing required last_name
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))
                ->without('id')
                ->without('last_name')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'last_name' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => $idNotToFind]);

        // Missing required e_mail
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))
                ->without('id')
                ->without('e_mail')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'e_mail' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => $idNotToFind]);

        // Missing required nationality
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))
                ->without('id')
                ->without('nationality')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'nationality' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => $idNotToFind]);

        // Inexistent nationality
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))
                ->without('id')
                ->with('nationality', 'XX')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'nationality' => [
                        'The nationality must be a valid ISO 3166-1 alpha-2 country code',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => $idNotToFind]);

        // Deleted nationality
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))
                ->without('id')
                ->with('nationality', 'IT')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'nationality' => [
                        'The nationality must be a valid ISO 3166-1 alpha-2 country code',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => $idNotToFind]);

        // Unallowed additional property.
        $this->json('POST',
            '/students',
            (new StudentBuilder('jack'))
                ->without('id')
                ->with('an_additional_property', 'an additional value')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'an_additional_property' => [
                        'code error_additional',
                        'value an additional value',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => $idNotToFind]);

        // @todo add invalid and minLength test

    }

    /**
     * Modify a student: success.
     */
    public function testModifyById()
    {

        $id = (new StudentBuilder('jane'))->build()['id'];

        // Success
        $this->json('PUT',
            '/students/' . $id,
            (new StudentBuilder('jane'))->without('id')->with('nationality', 'IE')->build()
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => (new StudentBuilder('jane'))->with('nationality', 'IE')->build()
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => $id, 'nationality' => 'IE'])
            ->notSeeInDatabase('students', ['id' => $id, 'nationality' => 'CA']);

        // Success, removed phone number
        $this->json('PUT',
            '/students/' . $id,
            (new StudentBuilder('jane'))->without('id')->without('phone')->build()
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => (new StudentBuilder('jane'))->without('phone')->build()
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => $id, 'phone' => null])
            ->notSeeInDatabase('students', ['id' => $id, 'phone' => '3333-11111111',]);

    }

    /**
     * Modify a student: failure.
     */
    public function testModifyByIdFailure()
    {

        // Non existing student
        $this->json('PUT',
            '/students/999',
            (new StudentBuilder('aaa'))->build()
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('students', ['id' => 999]);

        // Non existing student
        $this->json('PUT',
            '/students/abc',
            (new StudentBuilder('aaa'))->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('students', ['id' => 'abc']);

        $id = (new StudentBuilder('john'))->build()['id'];

        // Unallowed additional property.
        $this->json('PUT',
            '/students/' . $id,
            (new StudentBuilder('john'))
                ->without('id')
                ->with('first_name', 'John 1')
                ->with('last_name', 'Doe 1')
                ->with('an_additional_property', 'an additional value')
                ->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'an_additional_property' => [
                        'code error_additional',
                        'value an additional value',
                        'in body',
                    ]
                ]
            ])
            ->seeInDatabase('students', ['id' => $id])
            ->notSeeInDatabase('students', ['id' => $id, 'first_name' => 'John 1', 'last_name' => 'Doe 1']);

        // @todo add required and minLength tests

    }

    /**
     * Delete a student: success.
     */
    public function testDeleteById()
    {

        $id = (new StudentBuilder('jane'))->build()['id'];

        // Existing student
        $this->json('DELETE', '/students/' . $id)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('students', ['id' => $id]);

        $id = (new StudentBuilder('john'))->build()['id'];

        // Existing student with annotation, internship and educational activity attendance
        $this->json('DELETE', '/students/' . $id)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('students', ['id' => $id])
            ->notSeeInDatabase('annotations', ['id' => 1, 'student_id' => $id])
            ->notSeeInDatabase('internships', ['id' => 1, 'student_id' => $id])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'student_id' => $id])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 1, 'student_id' => $id]);

    }

    /**
     * Delete a student: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing student
        $this->json('DELETE', '/students/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('students', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/students/abc')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400);

        // Missing ID
        $this->json('DELETE', '/students/abc')
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'id' => [
                        'code error_type',
                        'value abc',
                        'expected integer',
                        'used string',
                        'in path',
                    ],
                ]
            ])
            ->seeStatusCode(400);

    }

}
