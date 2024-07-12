import sys
import os
from google.oauth2 import service_account
from googleapiclient.discovery import build
from googleapiclient.http import MediaIoBaseDownload, MediaIoBaseUpload
import pandas as pd
import io

def update_csv(department, content):
    SERVICE_ACCOUNT_FILE = '/path/to/your/credentials.json'  # 替換為您的credentials.json文件路徑
    SCOPES = ['https://www.googleapis.com/auth/drive']

    credentials = service_account.Credentials.from_service_account_file(
        SERVICE_ACCOUNT_FILE, scopes=SCOPES)

    service = build('drive', 'v3', credentials=credentials)

    FILE_ID = 'your_csv_file_id'  # 替換為您的CSV文件ID

    request = service.files().export_media(fileId=FILE_ID, mimeType='text/csv')
    fh = io.BytesIO()
    downloader = MediaIoBaseDownload(fh, request)
    done = False
    while done is False:
        status, done = downloader.next_chunk()

    fh.seek(0)
    df = pd.read_csv(fh)

    new_data = {
        'department': department,
        'content': content,
        'timestamp': pd.Timestamp.now()
    }
    new_df = pd.DataFrame([new_data])
    df = pd.concat([df, new_df], ignore_index=True)

    fh = io.BytesIO()
    df.to_csv(fh, index=False)
    fh.seek(0)

    media = MediaIoBaseUpload(fh, mimetype='text/csv')
    updated_file = service.files().update(
        fileId=FILE_ID,
        media_body=media
    ).execute()

if __name__ == "__main__":
    department = sys.argv[1]
    content = sys.argv[2]
    update_csv(department, content)
