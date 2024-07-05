Requirement 
    Nope.

Folder
    直接解壓縮所有檔案就可以了，但是data.zip要留在目錄裡面不能刪掉(不知道為什麼刪掉就跑不動了)
    root
        |- data
            |- embedding_character  
            |- ...
        |- emotion_dict
            |- ntusd-negative.txt
            |- ...
        emo.py
        ReadMe.txt

Input/Output
    1. Input:  '民眾陳情文字'       (DataType: String)
                python -u "..folderpath..\text_process.py" '北區公園北路5號附近，涼亭角落的地方時常有一位遊民在那邊當自己的家，遊民在那邊喝酒還會把玻璃酒瓶打碎在步道上，恐容易造成他人危險，遊民還會把自己的棉被堵在小運河造成堵塞，通報員警前往也沒有用，一直浪費資源，請局處強制安置遊民，也請工務局把涼亭的石桌石椅拆除不要讓遊民在那邊喝酒，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾 _x000D_'
    2. Output:  ['負面詞彙']        (Name: neg_words)   (DataType: List)  
                情緒分數            (Name: emo_score)   (DataType: float)   (range: -1 ~ +1)
