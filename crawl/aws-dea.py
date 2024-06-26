from udemy import Udemy
from api_factory import create_subject
from utils import renew_file
import os

subject_id = create_subject("AWS Data Engineer - Associate")

key = "aws-dea"
is_data_from_api = False

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}-udemy.data")

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f"images/{key}-1.jpeg",
    exam_name=key.upper() + " Udemy 1",
    quizz_ids=[
        6104086,
        6104090,
        6104092,
        6111610,
        6111614,
        6134598,
        6134600,
        6134610,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()

subject_id = create_subject("AWS Data Engineer - Associate 2")

key = "aws-dea"
is_data_from_api = False

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}-udemy-2.data")

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f"images/{key}-4.jpeg",
    exam_name=key.upper() + " Udemy 1",
    quizz_ids=[
        6120218,
        6120220,
        6120222,
        6120224,
        6120226,
        6120228,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()

# add real exam
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}-udemy-3.data")

a = Udemy(
    thumbnail=f"images/{key}-1.jpeg",
    exam_name=key.upper() + " Real Exam",
    quizz_ids=[
        6141676,
    ],
    exam_time=60,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()