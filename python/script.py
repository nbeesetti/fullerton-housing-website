
# documentation:
# https://pypi.org/project/youtube-transcript-api/

# run instructions:
# cd into fullerton-housing-website
# source myenv/bin/activate (on mac)
# myenv/bin/python python/script.py
# deactivate

# example inputs:
# video id: DktwbunEP8k
# search phrase: projects in orange county

import subprocess
import sys

# install youtube-transcript-api if not already installed
subprocess.check_call([sys.executable, "-m", "pip", "install", 'youtube-transcript-api'])

from youtube_transcript_api import YouTubeTranscriptApi

def get_combined_transcript(video_id: str) -> tuple:
    try:
        transcript = YouTubeTranscriptApi.get_transcript(video_id)
    
        combined_text = ""
        text_length_to_time_list = []

        total_text_length = 0
        for entry in transcript:
            start_timestamp = entry['start']
            subtitle_text = entry['text'].lower()

            combined_text += subtitle_text + " "

            total_text_length += len(subtitle_text) + 1
            text_length_to_time_list.append((total_text_length, start_timestamp))

        return combined_text.strip(), text_length_to_time_list
    
    except Exception as e:
        print(f"Exception in get_transcript(), Video: {video_id}, Error: {e}")
        return None

def find_phrase_in_combined_text(combined_text: str, search_phrase: str) -> int:
    return combined_text.find(search_phrase.lower())

def get_timestamp_from_index(text_length_to_time_list: list, substring_index: int) -> int:
    for total_length, start_timestamp in text_length_to_time_list:
        if substring_index < total_length:
            return int(start_timestamp)
    return None

def search_transcript(transcript: list, search_phrase: str) -> tuple:
    # combine into one to search across entries
    combined_transcript = ' '.join(subtitle_dict['text'] for subtitle_dict in transcript).lower()
    search_phrase = search_phrase.lower()

    substring_index = combined_transcript.find(search_phrase)
    if substring_index == -1: return None

    current_index = 0
    for entry in transcript:
        entry_text = entry['text'].lower()
        if substring_index < current_index + len(entry_text):
            return entry['start'], entry_text
        current_index += len(entry_text) + 1

    return None

def generate_youtube_link(video_id: str, start_time: int) -> str:
    return f"https://www.youtube.com/watch?v={video_id}&t={start_time}s"

def main(video_id: str, search_phrase: str) -> None:
    transcript = get_combined_transcript(video_id)
    if transcript:
        combined_transcript_text, text_length_to_time_list = get_combined_transcript(video_id)
        
        substring_index = find_phrase_in_combined_text(combined_transcript_text, search_phrase)

        if substring_index != -1:
            start_time = get_timestamp_from_index(text_length_to_time_list, substring_index)
            link = generate_youtube_link(video_id, start_time)
            print(f"YouTube Link: {link}")
        else:
            print("Search phrase not found in transcript")
    else:
        print("No transcript")

if __name__ == "__main__":
    print("------------------------------")
    video_id = input("Enter YouTube Video ID: ")
    search_phrase = input("Enter search phrase: ")
    main(video_id, search_phrase)
    print("------------------------------")
