from ApiFactory import ApiFactory, create_subject

subject_id = create_subject('AWS Database Specialty')


a = ApiFactory(
    thumbnail='images/dbs-1.jpeg',
    exam_name="DBS Udemy",
    quizz_ids=[
        4992398,
        4992420,
    ],
    exam_time=180,
    subject_id=subject_id,
)
a.run()
