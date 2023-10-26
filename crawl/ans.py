from udemy import Udemy
from api_factory import create_subject
from utils import renew_file
import os

subject_id = create_subject("AWS Advanced Networking Specialty")


key = "ans"
# remove raw data file
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}.data")
is_data_from_api = True
if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f"images/aws-{key}-1.jpeg",
    exam_name=key.upper() + " Udemy 1",
    quizz_ids=[
        5355502,
        5355504,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()

a = Udemy(
    thumbnail=f"images/aws-{key}-2.jpeg",
    exam_name=key.upper() + " Udemy 2",
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
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
