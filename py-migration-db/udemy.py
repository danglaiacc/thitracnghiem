from WebFactory import WebFactory


class Udemy(WebFactory):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)

    @property
    def question_text_class(self):
        return '.mc-quiz-question--question-prompt--2_dlz'

    @property
    def explanation_text_class(self):
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
        question_remove_strings = [
            ' class="ud-text-bold mc-quiz-question--question-prompt--2_dlz rt-scaffolding" data-purpose="safely-set-inner-html:rich-text-viewer:html" id="question-prompt"',
            '<p><br/></p>',
        ]
        for question_remove_string in question_remove_strings:
            question = question.replace(question_remove_string, '')
        return question

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

    def transform_explanation(self, explanation: str):
        remove_strings = [
            ' class="rt-scaffolding" data-purpose="safely-set-inner-html:rich-text-viewer:html" id="question-explanation"',
            ' class="mc-quiz-question--explanation--Q5KHQ"',
            ' class="ud-heading-md"',
            '<div><h4>Explanation</h4><div>',
            '<p><br/></p>',
        ]

        for remove_string in remove_strings:
            explanation = explanation.replace(
                remove_string,
                ''
            )
        return explanation
