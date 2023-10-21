from ApiFactory import ApiFactory, create_subject
from utils import renew_file
import os

subject_id = create_subject('AWS Machine Learning Specialty')


key = 'mls'
# remove raw data file
raw_data_path = os.path.join(os.getcwd(), 'raw-data', f'{key}.data')
renew_file(raw_data_path)

a = ApiFactory(
    thumbnail=f'images/aws-{key}-1.jpeg',
    exam_name=key.upper()+" Udemy 1",
    quizz_ids=[
        4755096,
        4755118,
        4755598,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
)
a.run()

a = ApiFactory(
    thumbnail=f'images/aws-{key}-2.jpeg',
    exam_name=key.upper()+" Udemy 2",
    quizz_ids=[
        5824438,
        5824440,
        5824442,
        5824444,
        5824446,
        5824448,
    ],
    exam_time=60,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
)
a.run()
