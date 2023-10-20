from ApiFactory import ApiFactory, create_subject
import os

subject_id = create_subject('Databricks Certified Data Engineer Associate')


key = 'dea'

a = ApiFactory(
    thumbnail=f'images/{key}-1.jpeg',
    exam_name='Databricks Data Engineer Associate',
    quizz_ids=[
        5596958,
        5606606,
        5608500,
        5609188,
        5609190,
    ],
    exam_time=90,
    subject_id=subject_id,
    raw_data_path=os.path.join(os.getcwd(), 'raw-data', f'{key}.data'),
)
a.run()
