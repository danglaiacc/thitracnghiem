from WebFactory import create_subject
from udemy import Udemy

subject_id = create_subject('Databricks Data Engineer Professional')


# Databricks Data Engineer Associate
folder_path = '/Users/lai/Downloads/exam/databricks'
for index in range(1, 3):
    file_path = f'{folder_path}/dep-{index}.html'

    u = Udemy(
        file_path=file_path,
        thumbnail='images/databricks-dep-1.jpeg',
        question_card_from=1,
        exam_name=f"Databricks DEP {index}",
        subject_id=subject_id,
        exam_time=120,
    )
    u.run()
