import os
import json
from datetime import datetime, timedelta

# Specify the directory containing JSON files
folder_path = "."
output_file = "all.data"

# Get a list of JSON files in the folder
json_files = [f for f in os.listdir(folder_path) if f.endswith(".json")]

# Initialize a list to hold all content
all_content = []

# Current timestamp
current_time = datetime.now()

# Read each JSON file
for index, json_file in enumerate(json_files):
    # Generate ID based on current timestamp
    timestamp_id = current_time.strftime("%Y%m%d%H%M%S")

    # Create full file path
    file_path = os.path.join(folder_path, json_file)

    # Read JSON content
    with open(file_path, "r") as file:
        content = json.load(file)
    now = current_time.strftime("%Y%m%d%H%M%S")
    print(now)

    write_content = f"{now}\n~~~\n{json.dumps(content)}\n"

    with open(output_file, "a+") as file:
        file.write(write_content)

    # Increment the timestamp by 1 second for the next file
    current_time += timedelta(seconds=1)

print(f"All JSON content has been written to {output_file}.")
