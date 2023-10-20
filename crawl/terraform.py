from ApiFactory import ApiFactory, create_subject
import os

subject_id = create_subject('Terraform Associate 2023')


key = 'terraform'

a = ApiFactory(
    thumbnail=f'images/{key}-1.jpeg',
    exam_name=key.upper()+" Udemy 1",
    quizz_ids=[
        4777084,
        4775582,
        5022888,
        5413604,
        5429662,
        5434842,
    ],
    exam_time=180,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', f'{key}.data'),
)
a.run()
