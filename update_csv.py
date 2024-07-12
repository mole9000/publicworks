import sys
import os
from google.oauth2 import service_account
from googleapiclient.discovery import build
from googleapiclient.http import MediaIoBaseDownload, MediaIoBaseUpload
import pandas as pd
import io

def update_csv(department, content):
    SERVICE_ACCOUNT_FILE = '/var/www/html/publicwork-cdfaf549b32b.json'  # 替換為您的credentials.json文件路徑
    SCOPES = ['https://www.googleapis.com/auth/drive']

    credentials = service_account.Credentials.from_service_account_file(
        SERVICE_ACCOUNT_FILE, scopes=SCOPES)

    service = build('drive', 'v3', credentials=credentials)

    FILE_ID = '1GT25Tye8ToxqE3rBqWs5v3Pc1MYwtXdtplDoeb76XJ0'  # 替換為您的CSV文件ID

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

def update_csv_imp(emofeedback_type, content):
    SERVICE_ACCOUNT_FILE = '/var/www/html/publicwork-cdfaf549b32b.json'  # 替換為您的credentials.json文件路徑
    SCOPES = ['https://www.googleapis.com/auth/drive']

    credentials = service_account.Credentials.from_service_account_file(
        SERVICE_ACCOUNT_FILE, scopes=SCOPES)

    service = build('drive', 'v3', credentials=credentials)

    FILE_ID = '1JDbycEIOsgp5n9J9-X0NtqJR87tE_s8uPlBpQQrqwZI'  # 替換為您的CSV文件ID

    request = service.files().export_media(fileId=FILE_ID, mimeType='text/csv')
    fh = io.BytesIO()
    downloader = MediaIoBaseDownload(fh, request)
    done = False
    while done is False:
        status, done = downloader.next_chunk()

    fh.seek(0)
    df = pd.read_csv(fh)

    new_data = {
        'emo_type': emofeedback_type,
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

def update_csv_re(item, subitem, content):
    SERVICE_ACCOUNT_FILE = '/var/www/html/publicwork-cdfaf549b32b.json'  # 替換為您的credentials.json文件路徑
    SCOPES = ['https://www.googleapis.com/auth/drive']

    credentials = service_account.Credentials.from_service_account_file(
        SERVICE_ACCOUNT_FILE, scopes=SCOPES)

    service = build('drive', 'v3', credentials=credentials)
 
    FILE_ID = '1gE2S4PiFJbUhafihnx8B24qCx_Yi_p59S_sEv8MhLOA'  # 替換為您的CSV文件ID

    request = service.files().export_media(fileId=FILE_ID, mimeType='text/csv')
    fh = io.BytesIO()
    downloader = MediaIoBaseDownload(fh, request)
    done = False
    while done is False:
        status, done = downloader.next_chunk()

    fh.seek(0)
    df = pd.read_csv(fh)

    new_data = {
        'mainitem': item,
        'subitem': subitem,
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
    feedback_type = sys.argv[1]
    if feedback_type == 'classifier':
      department = sys.argv[2]
      content = sys.argv[3]
      update_csv(department, content)
    elif feedback_type == 'importance':
      content = sys.argv[2]
      emofeedback_type = sys.argv[3]
      update_csv_imp(emofeedback_type, content)
    elif feedback_type == 'reply':
      content = sys.argv[2] 
      item = sys.argv[3]
      subitem = sys.argv[4]
      update_csv_re(item, subitem, content)
