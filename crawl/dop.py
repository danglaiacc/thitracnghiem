from udemy import Udemy
from api_factory import create_subject
from utils import renew_file
import os
from tutorial_dojo import TutorialDojo
from web_factory import parent_dir

subject_id = create_subject("AWS DevOps Engineer Professional - DOP-C02")

# create exam from tutorial dojo test

file_paths = {
    'raw-data/tutorial-dojo-dop1.html',
    'raw-data/tutorial-dojo-dop2.html',
    'raw-data/tutorial-dojo-dop3.html',
}

for index, path in enumerate(file_paths):
    file_path=os.path.join(parent_dir, path)
    u = TutorialDojo(
        file_path=path,
        thumbnail='images/aws-dop-tutorial-dojo.jpeg',
        subject_id=subject_id,
        exam_name=f"AWS DOP Tutorial {index}",
        exam_time=180,
    )
    u.run()

# create exam from udemy course
key = "dop"
is_data_from_api = False

# remove raw data file
raw_data_path = os.path.join(os.getcwd(), "raw-data", f"{key}.data")

if is_data_from_api:
    renew_file(raw_data_path)

a = Udemy(
    thumbnail="images/aws-dop-1.jpeg",
    exam_name="DOP Udemy",
    quizz_ids=[
        4724020,
        4716374,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()

a = Udemy(
    thumbnail="images/aws-dop-2.jpeg",
    exam_name="DOP Udemy 2",
    quizz_ids=[
        5794160,
        5794162,
        5794166,
        5794170,
        5794174,
        5794178,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=raw_data_path,
    is_data_from_api=is_data_from_api,
)
a.run()
