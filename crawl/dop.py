from ApiFactory import ApiFactory, create_subject
import os

subject_id = create_subject('AWS DevOps Engineer Professional - DOP-C02')


a = ApiFactory(
    thumbnail='images/aws-dop-1.jpeg',
    exam_name="DOP Udemy",
    quizz_ids=[
        4724020,
        4716374,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', 'dop.data'),
)
a.run()

a = ApiFactory(
    thumbnail='images/aws-dop-2.jpeg',
    exam_name="DOP Udemy 2",
    quizz_ids=[
        5794160,
        5794162,
        5794166,
        5794170,
        5794174,
        5794178,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', 'dop.data'),
)
a.run()