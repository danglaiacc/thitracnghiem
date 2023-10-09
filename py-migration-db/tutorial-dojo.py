import mysql.connector
from bs4 import BeautifulSoup
from uuid import uuid4
import re

'''
alias lai='cd ..; seed; cd -; py migrate-tutorial-dojo.py'
'''


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


def insert_to_db(file_path: str, exam_number: int):
    with open(file_path, 'r') as file:
        html_content = file.read()

    soup = BeautifulSoup(html_content, 'html.parser')
    question_cards = soup.select("li.wpProQuiz_listItem")

    # import to exam table
    exam_insert_query = f"INSERT INTO exams (uuid, id, name, thumbnail, time_minute, subject_id) VALUES (%s, %s, %s, %s, %s, 1)"
    cursor.execute(exam_insert_query, (get_uuid(), exam_number,
                   f'Tutorial Dojo {exam_number}', 'images/thumbnail2.jpeg', 180))

    exam_id = cursor.lastrowid

    for i in range(0, len(question_cards)):
        question_card = question_cards[i]

        question_text = str(question_card.select(
            '.wpProQuiz_question_text')[0]).replace('\n', '')

        question_text = re.sub(
            r'data-renderer-start-pos=\"\d+\"', '', question_text)
        question_text = re.sub(
            r'/\sstyle=".*?\"/gm', '', question_text)

        for remove_question_text in [
            ' class="wpProQuiz_question_text"',
            ' data-renderer-mark="true"',
            ' class="fabric-text-color-mark" data-renderer-mark="true" data-text-custom-color="#ff5630"',
            ' data-layout-section="true"',
            ' data-column-width="66.66"',
            ' data-layout-column="true"',
        ]:
            question_text = question_text.replace(remove_question_text, '')

        explaination = str(question_card.select(
            '.wpProQuiz_response')[0]).replace('\n', '')

        remove_expalination_strings = [
            ' class="wpProQuiz_response" style=""',
            ' class="wpProQuiz_correct" style=""',
            '<span>Correct</span>',
            'alt="" decoding="async" height="260" loading="lazy" ',
            ' style="display: block; margin-left: auto; margin-right: auto;" width="700"',
            ' rel="noopener"',
            ' class="wpProQuiz_AnswerMessage"',
            ' style="padding-left: 40px;"',
            ' class="fabric-text-color-mark" data-text-custom-color="#ff5630" style="--custom-text-color: #ff5630;"',
            ' style="display: block; margin-left: auto; margin-right: auto;"',
            '<span> Incorrect	</span>',
            '<div class="wpProQuiz_correct" style="display: none;"><p></p></div>',
            '<span>									Incorrect								</span>',
            ' class="wpProQuiz_incorrect" style=""',
            ' decoding="async"',
            ' loading="lazy"',
        ]

        for remove_string in remove_expalination_strings:
            explaination = explaination.replace(
                remove_string,
                ''
            )

        note = f"tr-{exam_number-2}.{i}"

        # insert to question
        question_insert_query = "INSERT INTO questions (uuid, text, explaination, note) VALUES (%s, %s, %s, %s)"
        cursor.execute(question_insert_query,
                       (get_uuid(), question_text, explaination, note))
        question_id = cursor.lastrowid

        # insert to exam question
        exam_question_insert_query = "INSERT INTO exam_questions (exam_id, question_id) VALUES (%s, %s)"
        cursor.execute(exam_question_insert_query, (exam_id, question_id))

        options = question_card.find(
            'ul', {'class': 'wpProQuiz_questionList'}
        ).find_all('li')

        total_correct_options = 0
        for option_html in options:
            # option_html = str(option.select('.wpProQuiz_questionListItem')[0])
            option_html = str(option_html)
            is_correct = int('wpProQuiz_answerCorrect' in option_html)
            total_correct_options += 1 if is_correct else 0

            option_html = re.sub(
                r'<span style="display:none;">.*?<\/span>', '', option_html)
            option_html = re.sub(r'data-pos="\d*"', '', option_html)
            option_html = re.sub(r'name="question_.*?"', '', option_html)
            option_html = re.sub(r'\svalue="\d"\/>', '>', option_html)
            option_html = re.sub(r'\s+', ' ', option_html)

            remove_option_strings = [
                ' class="wpProQuiz_questionListItem"',
                ' class="wpProQuiz_questionInput bbstyled" disabled="disabled"',
                ' class="wpProQuiz_questionListItem wpProQuiz_answerCorrect"',
                ' class="wpProQuiz_questionListItem wpProQuiz_answerCorrectIncomplete"',
                ' class="wpProQuiz_questionListItem wpProQuiz_answerIncorrect"',
                '<span class="input-style"></span> ',
            ]
            for remove_option_string in remove_option_strings:
                option_html = option_html.replace(remove_option_string, '')

            option_html = option_html.replace('<li > <label> ', '<li><label>')
            option_html = option_html.replace(
                ' </label> </li>', '</label></li>')
            option_insert_query = "INSERT INTO options (text, is_correct, question_id) VALUES (%s, %s, %s)"
            cursor.execute(option_insert_query, (str(
                option_html), is_correct, question_id))

        # update multi choice field
        if (total_correct_options > 1):
            update_question_query = "update questions set is_multichoice=%s where id=%s"
            cursor.execute(update_question_query, (1, question_id))


file_paths = {
    '/Users/lai/Downloads/exam/sap.tr-1.html': 3,
    '/Users/lai/Downloads/exam/sap.tr-2.html': 4,
    '/Users/lai/Downloads/exam/sap.tr-3.html': 5,
    '/Users/lai/Downloads/exam/sap.tr-4.html': 6,
    '/Users/lai/Downloads/exam/sap.tr-5.html': 7,
    '/Users/lai/Downloads/exam/sap.tr-6.html': 8,
}

for path, exam_number in file_paths.items():
    insert_to_db(path, exam_number)

conn.commit()
cursor.close()
conn.close()
