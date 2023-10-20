from ApiFactory import ApiFactory, create_subject
import os

subject_id = create_subject('AWS Security Specialty')


key = 'scs'

a = ApiFactory(
    thumbnail=f'images/aws-{key}-1.jpeg',
    exam_name=key.upper()+" Udemy 1",
    quizz_ids=[
        5789440,
        5789442,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', f'{key}.data'),
)
a.run()

a = ApiFactory(
    thumbnail=f'images/aws-{key}-2.jpeg',
    exam_name=key.upper()+" Udemy 2",
    quizz_ids=[
        5750708,
        5750710,
        5750712,
        5750714,
        5750716,
        5750718,
    ],
    exam_time=60,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', f'{key}.data'),
)
a.run()
