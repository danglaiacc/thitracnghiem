from WebFactory import create_subject
from udemy import Udemy

subject_id = create_subject('Databricks Data Engineer Associate')


# Databricks Data Engineer Associate
folder_path = '/Users/lai/Downloads/exam/databricks'
for index in range(1, 8):
    file_path = f'{folder_path}/dea-{index}.html'

    u = Udemy(
        file_path=file_path,
        thumbnail='images/databricks-dea.png',
        question_card_from=1,
        exam_name=f"Databricks DEA {index}",
        subject_id=subject_id,
        exam_time=90,
    )
    u.run()
