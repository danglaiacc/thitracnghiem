# AWS DevOps Engineer Professional - DOP

from Tutorial import TutorialDojo
from WebFactory import create_subject
from udemy import Udemy

subject_id = create_subject('AWS DevOps Engineer Professional')

# AWS DevOps Engineer Professional
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
        subject_id=subject_id,
        exam_name=f"SAP Udemy {index}",
        exam_time=180,
    )
    u.run()

file_paths = {
    '/Users/lai/Downloads/exam/sap.tr-1.html',
    '/Users/lai/Downloads/exam/sap.tr-2.html',
    '/Users/lai/Downloads/exam/sap.tr-3.html',
    '/Users/lai/Downloads/exam/sap.tr-4.html',
    '/Users/lai/Downloads/exam/sap.tr-5.html',
    '/Users/lai/Downloads/exam/sap.tr-6.html',
}

for index, path in enumerate(file_paths):
    u = TutorialDojo(
        file_path=path,
        thumbnail='images/thumbnail2.jpeg',
        subject_id=subject_id,
        exam_name=f"SAP Tutorial {index}",
        exam_time=180,
    )
    u.run()
