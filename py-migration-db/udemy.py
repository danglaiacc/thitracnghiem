from WebFactory import WebFactory


class Udemy(WebFactory):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)

    @property
    def question_text_class(self):
        return '.mc-quiz-question--question-prompt--2_dlz'

    @property
    def explaination_text_class(self):
        return 'div[class^="mc-quiz-question--explanation"]'

    @property
    def option_text_class(self):
        return 'ud-unstyled-list'

    @property
    def question_card_class(self):
        return "div[class^='detailed-result-panel--panel-row--']"

    def get_option_text_and_is_correct(self, option_html):
        is_correct = int(option_html.text.endswith('(Correct)'))
        option_html = str(option_html.select('.ud-heading-md')[0])
        return [option_html, is_correct]

    def transform_question(self, question: str):
        return question.replace(
            ' class="ud-text-bold mc-quiz-question--question-prompt--2_dlz rt-scaffolding" data-purpose="safely-set-inner-html:rich-text-viewer:html" id="question-prompt"',
            ''
        )

    def transform_option(self, option: str):
        remove_option_strings = [
            ' class="ud-heading-md"',
            ' class="mc-quiz-answer--answer-inner--3WH_P"',
            ' class="mc-quiz-answer--answer-body--1JtTQ rt-scaffolding" data-purpose="safely-set-inner-html:rich-text-viewer:html"',
            '<div class="ud-heading-sm mc-quiz-answer--correctness--3pFQG">(Correct)</div>',
            '<div class="ud-heading-sm mc-quiz-answer--correctness--3pFQG">(Incorrect)</div>',
            '<div><div><div>',
            '</div></div></div>',
        ]
        for remove_option_string in remove_option_strings:
            option = option.replace(remove_option_string, '')
        return option

    def transform_explaination(self, explaination: str):
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
        return explaination


# AWS Solution Architect Professional
file_paths = {
    '/Users/lai/Downloads/exam/sap.u1.1.html',
    '/Users/lai/Downloads/exam/sap.u1.2.html',
    '/Users/lai/Downloads/exam/sap.u1.3.html',
}

for index, path in enumerate(file_paths):
    u = Udemy(
        file_path=path,
        thumbnail='images/thumbnail1.jpeg',
        question_card_from=1,
        exam_name=f"SAP Udemy {index}",
    )
    u.run()

# Databricks Data Engineer Associate
folder_path = '/Users/lai/Downloads/exam/databricks'
for index in range(1, 8):
    print('start '+f'{folder_path}/dea-{index}.html')
    u = Udemy(
        file_path=f'{folder_path}/dea-{index}.html',
        thumbnail='images/thumbnail1.jpeg',
        question_card_from=1,
        exam_name=f"Databricks Data Engineer {index}",
        subject_id=2,
        exam_time=90
    )
    u.run()
    print('done '+f'{folder_path}/dea-{index}.html')
