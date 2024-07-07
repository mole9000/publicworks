#!/usr/bin/python
# -*- coding: utf-8 -*-

import pandas as pd
import numpy as np
import sys
import os
#from IPython.display import display
#from ckip_transformers.nlp import CkipWordSegmenter, CkipPosTagger, CkipNerChunker

def main(text_table):
    currentDir = os.path.dirname(os.path.abspath(__file__))
    # Read dictionary
    # posdict = open('./emotion_dict/ntusd-positive.txt', encoding="utf-8").readlines()
    # negdict = open('./emotion_dict/ntusd-negative.txt', encoding="utf-8").readlines()
    #　Degree dictionary
    mostdict = "./degree_dict/most.txt"    # k = 2.0
    verydict = "./degree_dict/very.txt"    # k = 1.5
    moredict = "./degree_dict/more.txt"    # k = 1.0
    ishdict = "./degree_dict/ish.txt"      # k = 0.5
    insufficientdict = "./degree_dict/insufficient.txt"    # k = 0.25

    # conver text_table to bigrams
    text_table = text_table.replace('，', "");
    text_table = text_table.replace('！', "");
    text_table = text_table.replace('_x000D_', "");
    result =  [text_table[x:x+2] for x in range(len(text_table) - 1)]

    neg_words = []
    emo_count = 0
    emo_score = 0

    with open(currentDir+'/emotion_dict/ntusd-positive.txt', 'r', encoding="utf-8") as posdict:
        # print("open postive dictionary")
        for x in posdict:
            line = x.rstrip()
            for key in result:
                if key == line:
                    # print(key)
                    emo_score += + 1
                    emo_count += 1
    with open(currentDir+'/emotion_dict/ntusd-negative.txt', 'r', encoding="utf-8") as negdict:
        # print("open negative dictionary")
        for y in negdict:
            line = y.rstrip()
            for key in result: 
                if key == line:
                    # print(key)
                    neg_words.append(key)
                    emo_score += - 1
                    emo_count += 1

    if emo_count!=0 :
        emo_score = round((emo_score/emo_count), 2)
    else:
        emo_score = 0

    # Show results
    #print(neg_words)# print("===負向辭彙===")
    print(emo_score)# print("===情緒分數===") 範圍-1~+1: 小於0負面 大於0正面


if __name__ == "__main__":
    text_table = sys.argv[1]
    
    #text_table = "北區公園北路5號附近，涼亭角落的地方時常有一位遊民在那邊當自己的家，遊民在那邊喝酒還會把玻璃酒瓶打碎在步道上，恐容易造成他人危險，遊民還會把自己的棉被堵在小運河造成堵塞，通報員警前往也沒有用，一直浪費資源，請局處強制安置遊民，也請工務局把涼亭的石桌石椅拆除不要讓遊民在那邊喝酒，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾"    
    
    main(text_table)
