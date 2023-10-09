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


def insert_to_db(file_path: str, exam_number: int):
    with open(file_path, 'r') as file:
        html_content = file.read()

    soup = BeautifulSoup(html_content, 'html.parser')
    question_cards = soup.select(
        "div[class^='detailed-result-panel--panel-row--']")

    # import to exam table
    exam_insert_query = f"INSERT INTO exams (uuid, id, name, thumbnail, time_minute, subject_id) VALUES (%s, %s, %s, %s, %s, 1)"
    cursor.execute(exam_insert_query, (get_uuid(), exam_number,
                   f'Udemy {exam_number}', 'images/thumbnail1.jpeg', 180))

    exam_id = cursor.lastrowid

    for i in range(1, len(question_cards)):
        question_card = question_cards[i]

        question_text = str(question_card.select(
            '.mc-quiz-question--question-prompt--2_dlz')[0])
        question_text = question_text.replace(
            ' class="ud-text-bold mc-quiz-question--question-prompt--2_dlz rt-scaffolding" data-purpose="safely-set-inner-html:rich-text-viewer:html" id="question-prompt"', '')
        explaination = str(question_card.select(
            'div[class^="mc-quiz-question--explanation"]')[0])

        remove_strings = [
            ' class="rt-scaffolding" data-purpose="safely-set-inner-html:rich-text-viewer:html" id="question-explanation"',
            ' class="mc-quiz-question--explanation--Q5KHQ"',
            ' class="ud-heading-md"',
        ]

        for remove_string in remove_strings:
            explaination = explaination.replace(
                remove_string,
                ''
            )

        note = f"u1.{exam_number}.{i}"

        # insert to question
        question_insert_query = "INSERT INTO questions (uuid, text, explaination, note) VALUES (%s, %s, %s, %s)"
        cursor.execute(question_insert_query,
                       (get_uuid(), question_text, explaination, note))
        question_id = cursor.lastrowid

        # insert to exam question
        exam_question_insert_query = "INSERT INTO exam_questions (exam_id, question_id) VALUES (%s, %s)"
        cursor.execute(exam_question_insert_query, (exam_id, question_id))

        options = question_card.find(
            'ul', {'class': 'ud-unstyled-list'}
        ).find_all('li')

        total_correct_options = 0
        for option in options:
            option_text = option.text
            option_html = str(option.select('.ud-heading-md')[0])
            is_correct = int(option_text.endswith('(Correct)'))
            total_correct_options += 1 if is_correct else 0

            # option_text = str(re.sub('\(Correct\)$', '', option_text))

            remove_option_strings = [
                ' class="ud-heading-md"',
                ' class="mc-quiz-answer--answer-inner--3WH_P"',
                ' class="mc-quiz-answer--answer-body--1JtTQ rt-scaffolding" data-purpose="safely-set-inner-html:rich-text-viewer:html"',
                '<div class="ud-heading-sm mc-quiz-answer--correctness--3pFQG">(Correct)</div>',
                '<div class="ud-heading-sm mc-quiz-answer--correctness--3pFQG">(Incorrect)</div>',
            ]
            for remove_option_string in remove_option_strings:
                option_html = option_html.replace(remove_option_string, '')

            option_insert_query = "INSERT INTO options (text, is_correct, question_id) VALUES (%s, %s, %s)"
            cursor.execute(option_insert_query, (str(
                option_html), is_correct, question_id))

        # update multi choice field
        if (total_correct_options > 1):
            update_question_query = "update questions set is_multichoice=%s where id=%s"
            cursor.execute(update_question_query, (1, question_id))


file_paths = {
    '/Users/lai/Downloads/exam/sap.u1.1.html': 1,
    '/Users/lai/Downloads/exam/sap.u1.2.html': 2,
}

for path, exam_number in file_paths.items():
    insert_to_db(path, exam_number)

conn.commit()
cursor.close()
conn.close()
