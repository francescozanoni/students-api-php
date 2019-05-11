<?php
declare(strict_types = 1);

class StudentsTest extends TestCase
{

    /**
     * Get all students.
     */
    public function testGet()
    {
        $john = (new StudentBuilder('john'))->build();
        $jane = (new StudentBuilder('jane'))->build();
        $joan = (new StudentBuilder('joan'))->build();

        $this->json('GET', '/students')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [$john, $jane, $joan,]
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get a student: success.
     */
    public function testGetById()
    {

        $john = (new StudentBuilder('john'))->build();

        // Existing
        $this->json('GET', '/students/' . $john['id'])
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $john
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

        $jack = (new StudentBuilder('jack'))->build();
        $jackNoId = (new StudentBuilder('jack'))->without('id')->build();

        // Valid data
        $this->json('POST', '/students', $jackNoId)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $jack
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => $jack['id']])
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

    }

    /**
     * Create a student: failure.
     */
    public function testCreateFailure()
    {

        $jack = (new StudentBuilder('jack'))->build();

        $jackNoIdNoFirstName = (new StudentBuilder('jack'))->without('id', 'first_name')->build();
        $jackNoIdNoLastName = (new StudentBuilder('jack'))->without('id', 'last_name')->build();
        $jackNoIdNoEmail = (new StudentBuilder('jack'))->without('id', 'e_mail')->build();
        $jackNoIdNoNationality = (new StudentBuilder('jack'))->without('id', 'nationality')->build();
        $jackNoIdInexistentNationality = (new StudentBuilder('jack'))->without('id')->with('nationality', 'XX')->build();
        $jackNoIdDeletedNationality = (new StudentBuilder('jack'))->without('id')->with('nationality', 'IT')->build();
        $jackNoIdUnallowedProperty = (new StudentBuilder('jack'))->without('id')->with('an_additional_property', 'an additional value')->build();

        // Missing required first_name
        $this->json('POST', '/students', $jackNoIdNoFirstName)
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
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

        // Missing required last_name
        $this->json('POST', '/students', $jackNoIdNoLastName)
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
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

        // Missing required e_mail
        $this->json('POST', '/students', $jackNoIdNoEmail)
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
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

        // Missing required nationality
        $this->json('POST', '/students', $jackNoIdNoNationality)
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
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

        // Inexistent nationality
        $this->json('POST', '/students', $jackNoIdInexistentNationality)
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
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

        // Deleted nationality
        $this->json('POST', '/students', $jackNoIdDeletedNationality)
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
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

        // Unallowed additional property.
        $this->json('POST', '/students', $jackNoIdUnallowedProperty)
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
            ->notSeeInDatabase('students', ['id' => $jack['id'] + 1]);

        // @todo add invalid and minLength test

    }

    /**
     * Modify a student: success.
     */
    public function testModifyById()
    {

        $jane = (new StudentBuilder('jane'))->build();
        $john = (new StudentBuilder('john'))->build();

        $janeNoIdChangedNationality = (new StudentBuilder('jane'))->without('id')->with('nationality', 'IE')->build();
        $janeChangedNationality = (new StudentBuilder('jane'))->with('nationality', 'IE')->build();
        $johnNoIdNoPhone = (new StudentBuilder('john'))->without('id', 'phone')->build();
        $johnNoPhone = (new StudentBuilder('john'))->without('phone')->build();

        // Success
        $this->json('PUT', '/students/' . $jane['id'], $janeNoIdChangedNationality)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $janeChangedNationality
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => $jane['id'], 'nationality' => 'IE'])
            ->notSeeInDatabase('students', ['id' => $jane['id'], 'nationality' => $jane['nationality']]);

        // Success, removed phone number
        $this->json('PUT', '/students/' . $john['id'], $johnNoIdNoPhone)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $johnNoPhone
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('students', ['id' => $john['id'], 'phone' => null])
            ->notSeeInDatabase('students', ['id' => $john['id'], 'phone' => $john['phone']]);

    }

    /**
     * Modify a student: failure.
     */
    public function testModifyByIdFailure()
    {

        $aaa = (new StudentBuilder('aaa'))->build();
        $john = (new StudentBuilder('john'))->build();
        $johnNoIdChangedFirstNameLastNameWithUnallowedProperty = (new StudentBuilder('john'))
            ->without('id')
            ->with('first_name', 'John 1')
            ->with('last_name', 'Doe 1')
            ->with('an_additional_property', 'an additional value')
            ->build();

        // Non existing student
        $this->json('PUT', '/students/999', $aaa)
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('students', ['id' => 999]);

        // Non existing student
        $this->json('PUT', '/students/abc', $aaa)
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

        // Unallowed additional property.
        $this->json('PUT',
            '/students/' . $john['id'],
            $johnNoIdChangedFirstNameLastNameWithUnallowedProperty
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
            ->seeInDatabase('students', ['id' => $john['id']])
            ->notSeeInDatabase('students', ['id' => $john['id'], 'first_name' => 'John 1', 'last_name' => 'Doe 1']);

        // @todo add required and minLength tests

    }

    /**
     * Delete a student: success.
     */
    public function testDeleteById()
    {

        $jane = (new StudentBuilder('jane'))->build();
        $john = (new StudentBuilder('john'))->build();

        // Existing student
        $this->json('DELETE', '/students/' . $jane['id'])
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('students', ['id' => $jane['id']]);

        // Existing student with annotation, internship and educational activity attendance
        $this->json('DELETE', '/students/' . $john['id'])
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('students', ['id' => $john['id']])
            ->notSeeInDatabase('annotations', ['id' => 1, 'student_id' => $john['id']])
            ->notSeeInDatabase('internships', ['id' => 1, 'student_id' => $john['id']])
            ->notSeeInDatabase('evaluations', ['id' => 1, 'student_id' => $john['id']])
            ->notSeeInDatabase('educational_activity_attendances', ['id' => 1, 'student_id' => $john['id']]);

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
