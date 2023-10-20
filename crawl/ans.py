from ApiFactory import ApiFactory, create_subject
import os

subject_id = create_subject('AWS Advanced Networking Specialty')


a = ApiFactory(
    thumbnail='images/aws-ans-1.jpeg',
    exam_name="ANS Udemy",
    quizz_ids=[
        5355502,
        5355504,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', 'ans.data'),
)
a.run()

a = ApiFactory(
    thumbnail='images/aws-ans-2.jpeg',
    exam_name="ANS Udemy 2",
    quizz_ids=[
        5788394,
        5788552,
        5788396,
        5788398,
        5788400,
        5788402,
    ],
    exam_time=60,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', 'ans.data'),
)
a.run()
