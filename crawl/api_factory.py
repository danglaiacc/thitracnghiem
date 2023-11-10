from abc import ABC, abstractmethod
from mysql import connector
import requests
import re
import os
import json
from utils import get_uuid, get_correct_answer_index, download_images, uuid4
from key import config_init


img_url_pattern = re.compile(
    r"(?P<url>https:\/\/.*\/(?P<filename>.*?(?:\bimg|png|jpeg|jpg\b)))"
)
connection_params = {
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "root",
    "database": "exam",
    # "host": "thi-trac-nghiem.mysql.database.azure.com",
    # "port": 3306,
    # "user": "lai",
    # "password": "@8@4NCMoJh5Xhy5h",
    # "database": "exam",
    # "ssl_ca": "/Users/lai/Desktop/thi-trac-nghiem/resources/certificates/db.crt.pem",
    # "ssl_disabled": False,
}


def create_subject(name: str):
    conn = connector.connect(**connection_params)
    cursor = conn.cursor()

    subject_insert_query = f"INSERT INTO subjects (uuid, name) VALUES (%s, %s)"
    cursor.execute(
        subject_insert_query,
        (
            str(uuid4()),
            name,
        ),
    )
    subject_id = cursor.lastrowid
    conn.commit()
    cursor.close()
    conn.close()

    return subject_id


img_urls = []


def download_image_explanation(
    explanation: str, folder_relative_path: str, folder_absolute_path: str
):
    # matched = img_url_pattern.findall(explanation)

    # for (img_url, img_filename) in matched:
    #     img_relative_path = f'{folder_relative_path}/{img_filename}'
    #     # replace img url by local path
    #     explanation = explanation.replace(img_url, img_relative_path)
    #     img_urls.append([img_url,  f'{folder_absolute_path}/{img_filename}'])

    return explanation


class ApiFactory(ABC):
    def __init__(
        self,
        thumbnail: str,
        exam_name: str,
        quizz_ids: list,
        raw_data_path: str,
        exam_time: int = 180,
        subject_id: int = 1,
        is_data_from_api=True,
    ) -> None:
        self.thumbnail = thumbnail
        self.exam_name = exam_name
        self.quizz_ids = quizz_ids
        self.exam_time = exam_time * 60  # convert min to second
        self.subject_id = subject_id
        self.raw_data_path = raw_data_path
        self.is_data_from_api = is_data_from_api
        self.img_folder = os.path.join(
            os.getcwd(), "public", "images", "subjects", str(subject_id)
        )
        # os.makedirs(self.img_folder)

        self.conn = connector.connect(**connection_params)
        self.cursor = self.conn.cursor()

    def get_data_from_api(self, quizz_id: int):
        config = config_init[self.config_key]
        response = requests.get(
            url=config['url'].format(quizz_id),
            cookies=config['cookies'],
            headers=config['headers'],
        ).json()
        with open(self.raw_data_path, "a") as file:
            file.write(str(quizz_id) + "\n~~~\n" + json.dumps(response) + "\n")
        return response

    def get_data_from_raw_file(self, exam_number: int):
        response_line = 3 * exam_number - 1
        with open(self.raw_data_path, "r") as file:
            lines = file.readlines()
            return json.loads(lines[response_line])

    def run(self):
        exam_number = 1
        total_question = total_correct_answers = 0
        for quizz_id in self.quizz_ids:
            exam_id = self.write_exam_to_db(f"{self.exam_name} {exam_number}")

            if self.is_data_from_api:
                data = self.get_data_from_api(quizz_id)
            else:
                data = self.get_data_from_raw_file(exam_number)

            exam_number += 1

            for item in data["results"]:
                total_question += 1
                explanation = (
                    item["prompt"]["explanation"]
                    if "explanation" in item["prompt"]
                    else ""
                )

                question_id = self.write_question_to_db(
                    item["prompt"]["question"],
                    explanation,
                    "no note",
                    exam_id,
                    1 if len(item["correct_response"]) > 1 else 0,
                )

                correct_answers = get_correct_answer_index(item["correct_response"])

                total_correct_answers += len(correct_answers)

                # write option to db
                answer_index = 0
                for option in item["prompt"]["answers"]:
                    self.write_option_to_db(
                        option,
                        1 if answer_index in correct_answers else 0,
                        question_id,
                    )
                    answer_index += 1

        download_images(img_urls)

        print(f"done with {total_question=} {total_correct_answers=}")
        # close connection
        self.conn.commit()
        self.cursor.close()
        self.conn.close()

    @property
    @abstractmethod
    def config_key(self) -> str:
        pass

    def write_exam_to_db(self, exam_name):
        exam_insert_query = f"INSERT INTO exams (uuid, name, thumbnail, time, subject_id) VALUES (%s, %s, %s, %s, %s)"
        self.cursor.execute(
            exam_insert_query,
            (
                get_uuid(),
                exam_name,
                self.thumbnail,
                self.exam_time,
                self.subject_id,
            ),
        )
        return self.cursor.lastrowid

    def write_question_to_db(
        self,
        question_text: str,
        explanation: str,
        note: str,
        exam_id: int,
        is_multichoice: int,
    ):
        explanation = download_image_explanation(
            explanation,
            f"./images/subject/{self.subject_id}",
            self.img_folder,
        )

        # insert to question
        question_insert_query = "INSERT INTO questions (uuid, text, explanation, note, is_multichoice) VALUES (%s, %s, %s, %s, %s)"
        self.cursor.execute(
            question_insert_query,
            (get_uuid(), question_text, explanation, note, is_multichoice),
        )

        question_id = self.cursor.lastrowid
        # insert to exam question
        exam_question_insert_query = (
            "INSERT INTO exam_questions (exam_id, question_id) VALUES (%s, %s)"
        )
        self.cursor.execute(exam_question_insert_query, (exam_id, question_id))

        return question_id

    def write_option_to_db(self, option_html: str, is_correct: bool, question_id: int):
        option_insert_query = (
            "INSERT INTO options (text, is_correct, question_id) VALUES (%s, %s, %s)"
        )
        self.cursor.execute(
            option_insert_query, (str(option_html), is_correct, question_id)
        )

    # def update_question_multichoice(self, question_id: int):
    #     update_question_query = f"update questions set is_multichoice=1 where id={question_id}"
    #     self.cursor.execute(update_question_query)
