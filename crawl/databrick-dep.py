from udemy import Udemy
from api_factory import create_subject
from utils import renew_file
import os

subject_id = create_subject("Databricks Certified Data Engineer Professional")


key = "databrick-dep"
is_data_from_api = False

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}-udemy.data")

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f"images/{key}-1.jpeg",
    exam_name="Databricks Data Engineer Professional 1",
    quizz_ids=[
        5888730,
        5888736,
    ],
    exam_time=120,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()