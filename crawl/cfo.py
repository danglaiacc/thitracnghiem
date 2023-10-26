from udemy import Udemy
from api_factory import create_subject
from utils import renew_file
import os

subject_id = create_subject("Snowflake SnowPro Advanced Architect Certification")
key = "cfo"
is_data_from_api = True

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}-udemy.data")

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f"images/snowflake-{key}-1.jpeg",
    exam_name=key.upper() + " Udemy 1",
    quizz_ids=[
        5053410,
        5053408,
        5053406,
        5053376,
        5053382,
        5053396,
    ],
    exam_time=120,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()

a = Udemy(
    thumbnail=f"images/snowflake-{key}-2.jpeg",
    exam_name=key.upper() + " Udemy 2",
    quizz_ids=[
        4747086,
        4761106,
        4763076,
        4762416,
        4761102,
        4762402,
    ],
    exam_time=120,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
