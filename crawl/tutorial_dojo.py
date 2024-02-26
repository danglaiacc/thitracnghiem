from web_factory import WebFactory
import re


class TutorialDojo(WebFactory):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)

    @property
    def question_text_class(self):
        return '.wpProQuiz_question_text'

    @property
    def explanation_text_class(self):
        return '.wpProQuiz_response'

    @property
    def option_text_class(self):
        return 'wpProQuiz_questionList'

    @property
    def question_card_class(self):
        return "li.wpProQuiz_listItem"

    def get_option_text_and_is_correct(self, option_html):
        option_html = str(option_html)
        is_correct = int('wpProQuiz_answerCorrect' in option_html)
        return option_html, is_correct

    def transform_question(self, question: str):
        question_text = re.sub(
            r'data-renderer-start-pos=\"\d+\"', '', question)
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
        return question_text

    def transform_option(self, option: str):
        option_html = re.sub(
            r'<span style="display:none;">.*?<\/span>', '', option)
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
            '<input type="radio">',
            '<input type="checkbox">',
            ' class="is-selected"',
            ' class=""',
            '<span class="input-style"></span> ',
        ]
        for remove_option_string in remove_option_strings:
            option_html = option_html.replace(remove_option_string, '')

        option_html = option_html.replace('<li > <label> ', '<p>')
        option_html = option_html.replace(' </label> </li>', '</p>')
        option_html = option_html.replace('<p><span><br/> ', '<p><span>')

        return option_html

    def transform_explanation(self, explanation: str):
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
            explanation = explanation.replace(
                remove_string,
                ''
            )
        return explanation
