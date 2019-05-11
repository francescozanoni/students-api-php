<?php
declare(strict_types = 1);

class AnnotationsTest extends TestCase
{

    /**
     * Get all annotations.
     */
    public function testGet()
    {
        $this->json('GET', '/annotations')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    (new AnnotationBuilder('first'))
                        ->with('student', (new StudentBuilder('john'))->build())
                        ->build()
                ]
            ])
            ->seeStatusCode(200);
    }

    /**
     * Get annotation by ID.
     */
    public function testGetById()
    {

        $data = (new AnnotationBuilder('first'))
            ->with('student', (new StudentBuilder('john'))->build())
            ->build();

        // Existing
        $this->json('GET', '/annotations/' . $data['id'])
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $data,
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get annotation by ID: failure.
     */
    public function testGetByIdFailure()
    {

        // Non existing
        $this->json('GET', '/annotations/9999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid ID
        $this->json('GET', '/annotations/abc')
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
     * Get student's annotations: success.
     */
    public function testGetRelatedToStudent()
    {

        $student = (new StudentBuilder('john'))->build();

        // Existing
        $this->json('GET', '/students/' . $student['id'] . '/annotations')
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource(s) found',
                'data' => [
                    (new AnnotationBuilder('first'))->build()
                ],
            ])
            ->seeStatusCode(200);

    }

    /**
     * Get student's annotations: failure.
     */
    public function testGetRelatedToStudentFailure()
    {

        // Non existing annotations
        $this->json('GET', '/students/' . (new StudentBuilder('jane'))->build()['id'] . '/annotations')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Non existing student
        $this->json('GET', '/students/999/annotations')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404);

        // Invalid student ID
        $this->json('GET', '/students/abc/annotations')
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
     * Create a student's annotation.
     */
    public function testCreateRelatedToStudent()
    {

        $student = (new StudentBuilder('john'))->build();
        $data = (new AnnotationBuilder('second'))->build();

        // Existing student
        $this->json('POST',
            '/students/' . $student['id'] . '/annotations',
            (new AnnotationBuilder('second'))->without('id')->build()
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => $data,
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('annotations', ['id' => $data['id'], 'student_id' => $student['id']])
            ->notSeeInDatabase('annotations', ['id' => $data['id'] + 1]);

    }

    /**
     * Create a student's annotation: failure.
     */
    public function testCreateRelatedToStudentFailure()
    {

        $idNotNoFind = (new AnnotationBuilder('second'))->build()['id'] + 1;
        $deletedStudentId = (new StudentBuilder('jim'))->build()['id'];
        $studentId = (new StudentBuilder('john'))->build()['id'];

        // Non existing student
        $this->json('POST',
            '/students/999/annotations',
            (new AnnotationBuilder('second'))->without('id')->build()
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind])
            ->notSeeInDatabase('annotations', ['student_id' => 999]);

        // Deleted student
        $this->json('POST',
            '/students/' . $deletedStudentId . '/annotations',
            (new AnnotationBuilder('second'))->without('id')->build()
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind])
            ->notSeeInDatabase('annotations', ['student_id' => $deletedStudentId]);

        // Invalid student ID
        $this->json('POST',
            '/students/abc/annotations',
            (new AnnotationBuilder('second'))->without('id')->build()
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
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind])
            ->notSeeInDatabase('annotations', ['student_id' => 'abc']);

        // Missing required title
        $this->json('POST',
            '/students/' . $studentId . '/annotations',
            (new AnnotationBuilder('second'))->without('id')->without('title')->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'title' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind]);

        // Too short title
        $this->json('POST',
            '/students/' . $studentId . '/annotations',
            (new AnnotationBuilder('second'))->without('id')->with('title', 'A')->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'title' => [
                        'code error_minLength',
                        'length 1',
                        'min 3',
                        'value A',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind]);

        // Missing required content
        $this->json('POST',
            '/students/' . $studentId . '/annotations',
            (new AnnotationBuilder('second'))->without('id')->without('content')->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'content' => [
                        'code error_required',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind]);

        // Too short content
        $this->json('POST',
            '/students/' . $studentId . '/annotations',
            (new AnnotationBuilder('second'))->without('id')->with('content', 'A')->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'content' => [
                        'code error_minLength',
                        'length 1',
                        'min 3',
                        'value A',
                        'in body',
                    ]
                ]
            ])
            ->seeStatusCode(400)
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind]);

        // Unallowed additional property.
        $this->json('POST',
            '/students/' . $studentId . '/annotations',
            (new AnnotationBuilder('second'))->without('id')->with('an_additional_property', 'an additional value')->build()
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
            ->notSeeInDatabase('annotations', ['id' => $idNotNoFind]);

    }

    /**
     * Modify an annotation: success.
     */
    public function testModifyById()
    {

        $id = (new AnnotationBuilder('first'))->build()['id'];
        $title = (new AnnotationBuilder('first'))->build()['title'];

        // Success
        $this->json('PUT',
            '/annotations/' . $id,
            (new AnnotationBuilder('first'))->without('id')->with('title', 'First title modified')->build()
        )
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource successfully retrieved/created/modified',
                'data' => (new AnnotationBuilder('first'))
                    ->with('title', 'First title modified')
                    ->with('student', (new StudentBuilder('john'))->build())
                    ->build()
            ])
            ->seeStatusCode(200)
            ->seeInDatabase('annotations', ['id' => $id, 'title' => 'First title modified'])
            ->notSeeInDatabase('annotations', ['id' => $id, 'title' => $title]);

    }

    /**
     * Modify an annotation: failure.
     */
    public function testModifyByIdFailure()
    {

        $idNotToBeFound = (new AnnotationBuilder('second'))->build()['id'] + 1;

        // Invalid ID
        $this->json('PUT',
            '/annotations/abc',
            (new AnnotationBuilder('second'))->without('id')->build()
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
            ->notSeeInDatabase('annotations', ['id' => 'abc'])
            ->notSeeInDatabase('annotations', ['id' => $idNotToBeFound]);

        // Non existing ID
        $this->json('PUT',
            '/annotations/999',
            (new AnnotationBuilder('second'))->without('id')->build()
        )
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('annotations', ['id' => 999])
            ->notSeeInDatabase('annotations', ['id' => $idNotToBeFound]);

        $unmodifiedAnnotationId = (new AnnotationBuilder('first'))->build()['id'];

        // Unallowed additional property.
        $this->json('PUT',
            '/annotations/' . $unmodifiedAnnotationId,
            (new AnnotationBuilder('first'))
                ->without('id')
                ->with('title', 'First title 1')
                ->with('content', 'First content 1')
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
            ->seeInDatabase('annotations', ['id' => $unmodifiedAnnotationId])
            ->notSeeInDatabase('annotations', ['id' => $unmodifiedAnnotationId, 'title' => 'First title 1', 'content' => 'First content 1']);

        // Missing required title
        $this->json('PUT',
            '/annotations/' . $unmodifiedAnnotationId,
            (new AnnotationBuilder('first'))->without('id')->without('title')->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'title' => [
                        'code error_required',
                        'in body',
                    ],
                ]
            ])
            ->seeStatusCode(400);

        // Missing required content
        $this->json('PUT',
            '/annotations/' . $unmodifiedAnnotationId,
            (new AnnotationBuilder('first'))->without('id')->without('content')->build()
        )
            ->seeJsonEquals([
                'status_code' => 400,
                'status' => 'Bad Request',
                'message' => 'Request is not valid',
                'data' => [
                    'content' => [
                        'code error_required',
                        'in body',
                    ],
                ]
            ])
            ->seeStatusCode(400);

        // @todo add further tests related to invalid attribute format

    }

    /**
     * Delete an annotation: success.
     */
    public function testDeleteById()
    {

        $deletedAnnotationId = (new AnnotationBuilder('first'))->build()['id'];

        // Existing annotation
        $this->json('DELETE', '/annotations/' . $deletedAnnotationId)
            ->seeJsonEquals([
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Resource deleted',
            ])
            ->seeStatusCode(200)
            ->notSeeInDatabase('annotations', ['id' => $deletedAnnotationId]);

    }

    /**
     * Delete an annotation: failure.
     */
    public function testDeleteByIdFailure()
    {

        // Non existing annotation
        $this->json('DELETE', '/annotations/999')
            ->seeJsonEquals([
                'status_code' => 404,
                'status' => 'Not Found',
                'message' => 'Resource(s) not found',
            ])
            ->seeStatusCode(404)
            ->notSeeInDatabase('annotations', ['id' => 999]);

        // Invalid ID
        $this->json('DELETE', '/annotations/abc')
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
            ->notSeeInDatabase('annotations', ['id' => 'abc']);

    }

}
