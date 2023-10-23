from ApiFactory import ApiFactory, create_subject
from utils import renew_file
import os

subject_id = create_subject('Terraform Associate 2023')


key = 'terraform'
is_data_from_api = True

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), 'raw-data', f'{key}.data')

if is_data_from_api:
    renew_file(raw_data_path)

a = ApiFactory(
    thumbnail=f'images/{key}-1.jpeg',
    exam_name=key.upper()+" Udemy 1",
    quizz_ids=[
        4777084,
        4775582,
        5022888,
        5413604,
        5429662,
        5434842,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=renew_file,
    is_data_from_api=is_data_from_api,
)
a.run()
