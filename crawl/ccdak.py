from udemy import Udemy
from api_factory import create_subject
from utils import renew_file
import os

subject_id = create_subject("CCDAK Confluent Certified Developer for Apache Kafka")

is_data_from_api = True

key = "ccdak"
# remove raw data file
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}.data")
renew_file(raw_data_path)
if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f"images/{key}-1.jpeg",
    exam_name=key.upper() + " Udemy 1",
    quizz_ids=[
        4581118,
        4581122,
        4581124,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
