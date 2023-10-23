from ApiFactory import ApiFactory, create_subject
from utils import renew_file
import os

subject_id = create_subject('Databricks Certified Data Engineer Associate')


key = 'dea'
is_data_from_api = True

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), 'raw-data', f'{key}.data')

if is_data_from_api:
    renew_file(raw_data_path)

a = ApiFactory(
    thumbnail=f'images/{key}-1.jpeg',
    exam_name='Databricks Data Engineer Associate 1',
    quizz_ids=[
        5596958,
        5606606,
        5608500,
        5609188,
        5609190,
    ],
    exam_time=90,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()

a = ApiFactory(
    thumbnail=f'images/{key}-2.jpeg',
    exam_name='Databricks Data Engineer Associate 2',
    quizz_ids=[
        5731990,
        5732132,
    ],
    exam_time=90,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
