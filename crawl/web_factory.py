import os
from abc import ABC, abstractmethod
from uuid import uuid4

from bs4 import BeautifulSoup
from dotenv import load_dotenv
from mysql import connector

current_dir = os.path.dirname(os.path.abspath(__file__))
parent_dir = os.path.dirname(current_dir)
dotenv_path = os.path.join(parent_dir, ".env")
load_dotenv(dotenv_path)


def get_uuid():
    return str(uuid4())


connection_params = {
    "host": os.getenv("DB_HOST2"),
    "port": os.getenv("DB_PORT"),
    "user": os.getenv("DB_USERNAME"),
    "password": os.getenv("DB_PASSWORD"),
    "database": os.getenv("DB_DATABASE"),
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


class WebFactory(ABC):
    def __init__(
        self,
        file_path: str,
        thumbnail: str,
        exam_name: str,
        question_card_from: int = 0,
        exam_time: int = 180,
        subject_id: int = 1,
    ) -> None:
        self.file_path = file_path
        self.thumbnail = thumbnail
        self.question_card_from = question_card_from
        self.exam_name = exam_name
        self.exam_time = exam_time * 60  # convert min to second
        self.subject_id = subject_id
        self.conn = connector.connect(**connection_params)
        self.cursor = self.conn.cursor()

    def read_source(self):
        with open(self.file_path, "r", encoding='cp437') as file:
            html_content = file.read()

        return BeautifulSoup(html_content, "html.parser")

    def run(self):
        exam_id = self.write_exam_to_db()
        soup = self.read_source()
        question_cards = soup.select(self.question_card_class)

        for i in range(self.question_card_from, len(question_cards)):
            question_card = question_cards[i]

            question_text = str(
                question_card.select(self.question_text_class)[0]
            ).replace("\n", "")

            explanation = question_card.select(self.explanation_text_class)
            explanation = "" if len(explanation) == 0 else str(explanation[0])

            note = f"{self.exam_name} {i}"

            # create question
            question_id = self.write_question_to_db(
                question_text,
                explanation,
                note,
                exam_id,
            )

            # create options
            is_multi_choice = self.process_question(
                question_card,
                question_id,
            )

            # check question is multichoice then update in questions table
            is_multi_choice and self.update_question_multichoice(question_id)

        # close connection
        self.conn.commit()
        self.cursor.close()
        self.conn.close()

    def process_question(self, question_card, question_id: int):
        # transform option
        options = question_card.select_one(f"ul.{self.option_text_class}").find_all(
            "li", recursive=False
        )

        total_correct_options = 0
        for option_html in options:
            [option_text, is_correct] = self.get_option_text_and_is_correct(option_html)
            total_correct_options += 1 if is_correct else 0

            option_text = self.transform_option(option_text)
            self.write_option_to_db(
                option_text,
                is_correct,
                question_id,
            )
        return total_correct_options > 1

    @abstractmethod
    def get_option_text_and_is_correct(self, option_html):
        pass

    @property
    @abstractmethod
    def option_text_class(self):
        pass

    @property
    @abstractmethod
    def explanation_text_class(self):
        pass

    @property
    @abstractmethod
    def question_text_class(self):
        pass

    @property
    @abstractmethod
    def question_card_class(self):
        pass

    @abstractmethod
    def transform_question(self, question: str):
        pass

    @abstractmethod
    def transform_option(self, option: str):
        pass

    @abstractmethod
    def transform_explanation(self, explanation: str):
        pass

    def write_exam_to_db(self):
        exam_insert_query = f"INSERT INTO exams (uuid, name, thumbnail, time, subject_id) VALUES (%s, %s, %s, %s, %s)"
        self.cursor.execute(
            exam_insert_query,
            (
                get_uuid(),
                self.exam_name,
                self.thumbnail,
                self.exam_time,
                self.subject_id,
            ),
        )
        return self.cursor.lastrowid

    def write_question_to_db(
        self, question_text: str, explanation: str, note: str, exam_id: int
    ):
        question_text = self.transform_question(question_text)
        explanation = self.transform_explanation(explanation)

        # insert to question
        question_insert_query = "INSERT INTO questions (uuid, text, explanation, note) VALUES (%s, %s, %s, %s)"
        self.cursor.execute(
            question_insert_query, (get_uuid(), question_text, explanation, note)
        )

        question_id = self.cursor.lastrowid
        # insert to exam question
        exam_question_insert_query = (
            "INSERT INTO exam_questions (exam_id, question_id) VALUES (%s, %s)"
        )
        self.cursor.execute(exam_question_insert_query, (exam_id, question_id))

        return question_id

    def write_option_to_db(self, option_html: str, is_correct: bool, question_id: int):
        option_html = self.transform_option(option_html)

        option_insert_query = (
            "INSERT INTO options (text, is_correct, question_id) VALUES (%s, %s, %s)"
        )
        self.cursor.execute(
            option_insert_query, (str(option_html), is_correct, question_id)
        )

    def update_question_multichoice(self, question_id: int):
        update_question_query = (
            f"update questions set is_multichoice=1 where id={question_id}"
        )
        self.cursor.execute(update_question_query)
