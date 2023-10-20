from ApiFactory import ApiFactory, create_subject
import os

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
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', 'dbs.data'),
)
a.run()
