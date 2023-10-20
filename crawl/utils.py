import concurrent.futures
import requests
from uuid import uuid4
import os


def get_uuid():
    return str(uuid4())


def renew_file(file_path):
    if os.path.exists(file_path):
        try:
            # Remove the file
            os.remove(file_path)
            print(f"File '{file_path}' has been removed.")
        except OSError as e:
            print(f"Error: {e}")


def download_images(img_urls):
    def save_image_from_url(url, output_path):
        image = requests.get(url)
        with open(output_path, "wb") as f:
            f.write(image.content)

    with concurrent.futures.ThreadPoolExecutor(
        max_workers=5
    ) as executor:
        future_to_url = {
            executor.submit(save_image_from_url, url, output_path): url
            for [url, output_path] in img_urls
        }
        for future in concurrent.futures.as_completed(
            future_to_url
        ):
            url = future_to_url[future]
            try:
                future.result()
            except Exception as exc:
                print(
                    "%r generated an exception: %s" % (url, exc)
                )


def get_correct_answer_index(letters):
    return [ord(letter) - ord('a') for letter in letters]
