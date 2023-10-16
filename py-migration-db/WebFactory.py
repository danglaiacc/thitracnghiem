from abc import ABC, abstractmethod
from bs4 import BeautifulSoup
from mysql import connector
from uuid import uuid4


def get_uuid():
    return str(uuid4())


class WebFactory(ABC):
    def __init__(self, file_path: str, thumbnail: str, exam_name: str, question_card_from: int = 0) -> None:
        self.file_path = file_path
        self.thumbnail = thumbnail
        self.question_card_from = question_card_from
        self.exam_name = exam_name
        self.conn = connector.connect(
            host="localhost",
            port=3306,
            user="root",
            password="root",
            database="exam",
        )
        self.cursor = self.conn.cursor()

    def read_source(self):
        with open(self.file_path, 'r') as file:
            html_content = file.read()

        return BeautifulSoup(html_content, 'html.parser')

    def run(self):
        exam_id = self.write_exam_to_db()
        soup = self.read_source()
        question_cards = soup.select(
            self.question_card_class
        )

        for i in range(self.question_card_from, len(question_cards)):
            question_card = question_cards[i]

            question_text = self.transform_question(
                str(question_card.select(
                    self.question_text_class)[0])
                .replace('\n', '')
            )

            explaination_text = self.transform_question(
                str(question_card.select(
                    self.explaination_text_class)[0])
                .replace('\n', '')
            )

            note = f'{self.exam_name} {i}'

            # create question
            question_id = self.write_question_to_db(
                question_text,
                explaination_text,
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

    def process_question(self, question_card,  question_id: int):

        # transform option
        options = question_card.find(
            'ul', {'class': self.option_text_class}
        ).find_all('li')

        total_correct_options = 0
        for option_html in options:
            [option_text, is_correct] = self.get_option_text_and_is_correct(
                option_html)
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
    def explaination_text_class(self):
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
    def transform_explaination(self, explaination: str):
        pass

    def write_exam_to_db(self):
        exam_insert_query = f"INSERT INTO exams (uuid, name, thumbnail, time, subject_id) VALUES (%s, %s, %s, %s, 1)"
        self.cursor.execute(
            exam_insert_query, (
                get_uuid(),
                self.exam_name,
                self.thumbnail,
                180,
            )
        )
        return self.cursor.lastrowid

    def write_question_to_db(self, question_text: str, explaination: str, note: str, exam_id: int):
        question_text = self.transform_question(question_text)
        explaination = self.transform_explaination(explaination)

        # insert to question
        question_insert_query = "INSERT INTO questions (uuid, text, explaination, note) VALUES (%s, %s, %s, %s)"
        self.cursor.execute(question_insert_query,
                            (get_uuid(), question_text, explaination, note))

        question_id = self.cursor.lastrowid
        # insert to exam question
        exam_question_insert_query = "INSERT INTO exam_questions (exam_id, question_id) VALUES (%s, %s)"
        self.cursor.execute(exam_question_insert_query,
                            (exam_id, question_id))

        return question_id

    def write_option_to_db(self, option_html: str, is_correct: bool, question_id: int):
        option_html = self.transform_option(option_html)

        option_insert_query = "INSERT INTO options (text, is_correct, question_id) VALUES (%s, %s, %s)"
        self.cursor.execute(option_insert_query, (
            str(option_html), is_correct, question_id))

    def update_question_multichoice(self, question_id: int):
        update_question_query = f"update questions set is_multichoice=1 where id={question_id}"
        self.cursor.execute(update_question_query)
