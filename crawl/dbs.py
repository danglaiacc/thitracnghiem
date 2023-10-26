from udemy import Udemy, create_subject
from utils import renew_file
import os

subject_id = create_subject('AWS Database Specialty')

is_data_from_api = True
key='dbs'

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), 'raw-data', f'{key}.data')

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f'images/{key}-1.jpeg',
    exam_name="DBS Udemy",
    quizz_ids=[
        4992398,
        4992420,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
