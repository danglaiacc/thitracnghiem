from udemy import Udemy, create_subject
from utils import renew_file
import os

subject_id = create_subject('AWS Security Specialty')


key = 'scs'
is_data_from_api = True

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), 'raw-data', f'{key}.data')

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f'images/aws-{key}-1.jpeg',
    exam_name=key.upper()+" Udemy 1",
    quizz_ids=[
        5789440,
        5789442,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()

a = Udemy(
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
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
