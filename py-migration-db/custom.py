import mysql.connector
from bs4 import BeautifulSoup
from uuid import uuid4


def get_uuid():
    return str(uuid4())


# Establish a connection to the MySQL server
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="root",
    database="exam"
)
cursor = conn.cursor()

def insert_more():
    # create exam
    exam_insert_query = f"INSERT INTO exams (uuid, id, name, thumbnail, time_minute, subject_id, allow_shuffle) VALUES (%s, %s, %s, %s, %s, 1, 0)"
    cursor.execute(exam_insert_query, (get_uuid(), 100,
                   f'Difficult question', 'images/aws-sap-3.png', 180))

insert_more()
conn.commit()
cursor.close()
conn.close()
