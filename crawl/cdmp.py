from udemy import Udemy
from api_factory import create_subject
from utils import renew_file
import os

subject_id = create_subject("Certified Data Management Professional")


key = "das"
is_data_from_api = False

raw_data_path = os.path.join(os.getcwd(), "raw-data", f"cdmp-udemy-2.data")

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail=f"images/aws-{key}-2.jpeg",
    exam_name="CDMP 3",
    quizz_ids=[
# 20250112115751,
# 20250112115752,
# 20250112115753,
# 20250112115754,
# 20250112115755,
# 20250112115756,
# 20250112115757,
20250221151257,
20250221151258,
20250221151259,
20250221151300,
20250221151301,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
