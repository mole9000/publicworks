#!/usr/bin/python
# -*- coding: utf-8 -*-

# standard import
import json
import os
import sys
import time

# third-party import
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from statistics import mean
from ckiptagger import data_utils, construct_dictionary, WS, POS, NER
import gensim #pip install gensim #要先安裝Microsoft Visual C++ 14.0 #https://visualstudio.microsoft.com/zh-hant/visual-cpp-build-tools/ #安裝C++桌面開發 #http://www.8fe.com/jiaocheng/7046.html
import jieba
from gensim.corpora import WikiCorpus
from datetime import datetime as dt
from typing import List
from gensim.models.word2vec import Word2Vec
import lightgbm as lgb

def ana(test_input):
    currentDir = os.path.dirname(os.path.abspath(__file__))
    # 用CKIIP做斷詞
    # 使用 GPU：
    #    1. 安裝 tensorflow-gpu (請見安裝說明)
    #    2. 設定 CUDA_VISIBLE_DEVICES 環境變數，例如：os.environ["CUDA_VISIBLE_DEVICES"] = "0"
    #    3. 設定 disable_cuda=False，例如：ws = WS("./data", disable_cuda=False)
    # 使用 CPU：
    ws = WS(currentDir+"/data/data_CKIP")
    pos = POS(currentDir+"/data/data_CKIP")
    ner = NER(currentDir+"/data/data_CKIP")
    
    text_table = pd.DataFrame()
    
    # 開始斷詞
    text_table['word_text']='\n'
    text_table['pos_text']='\n'
    text_table['entity']='\n'
    text_table['subject']='\n'


    text_table['subject'] = [test_input]
    word_sentence_list = ws(
      text_table['subject'],
      # sentence_segmentation = True, # To consider delimiters
      # segment_delimiter_set = {",", "。", ":", "?", "!", ";"}), # This is the defualt set of delimiters
      # recommend_dictionary = dictionary1, # words in this dictionary are encouraged
      # coerce_dictionary = dictionary2, # words in this dictionary are forced
    )
    #word_sentence_list
    pos_sentence_list = pos(word_sentence_list)
    entity_sentence_list = ner(word_sentence_list, pos_sentence_list)
    text_table['word_text'] = word_sentence_list
    text_table['pos_text'] = pos_sentence_list
    text_table['entity'] = entity_sentence_list
    
    # 刪除一些不用用的詞性

    stop_pos = set(['Nep', 'Nh', 'Nb']) # 這 3 種詞性不保留
    remove_str = ['，'] # word_text 中有 '，' 都去掉，這裡可以自己新增

    for i in range(len(text_table)): 
        word_text_list=[]
        pos_text_list=[]

        for j in range(len(text_table.iloc[i].pos_text[0])):
            pos_str = text_table.iloc[i].pos_text[0][j]
            word_str = text_table.iloc[i].word_text[0][j]

            # clean word_str: eg. '派工，' -> '派工'
            for k in range(len(remove_str)):
                word_str.replace(remove_str[k],'')
            # remove '\n' in word_str
            word_str = word_str.strip()

            # 只留名詞、動詞、形容詞、動詞前程度副詞(Dfa)、動詞後程度副詞(Dfb)
            # 去掉名詞裡的某些詞性
            if (pos_str[0] == 'N' or pos_str[0] == 'V' or pos_str[0] == 'A' or pos_str[0] == 'D') and pos_str not in stop_pos :
                pos_text_list.append(pos_str)
                word_text_list.append(word_str)
        text_table.iloc[i]['pos_text'][0] = pos_text_list
        text_table.iloc[i]['word_text'][0] = word_text_list
    
    #print(text_table)
    
    characters = "'[]○（），。() "
    for x in range(len(characters)):
        text_table['word_text'][0] = str(text_table['word_text'][0]).replace(characters[x],"")
        
    # 載入預訓練詞向量模型
    model = gensim.models.KeyedVectors.load_word2vec_format(currentDir+"/data/y_360W_cbow_2D_300dim_2020v1.bin", unicode_errors='ignore', binary=True)
    
    # 載入k-means的csv檔
    K_means = pd.read_csv(currentDir+"/data/alldata_K-means_v3.csv")
    
    level_dict ={}
    for char in text_table['word_text'][0].split(','):
        try:
            # 將詞轉成300維向量
            vec = model[char]
            level_dict[char] = vec
        except KeyError as e:
            pass
            
    input_W2V= pd.DataFrame.from_dict(level_dict, orient='index')
    
    group_tmp = []

    for char in input_W2V.index:
        sim_tmp =[]
        for idx in range(len(K_means)):        
            #print(char)
            try:
                # 將詞轉成300維向量
                #vec = model[char]
                sim = model.similarity(char,K_means['word'][idx])
                sim_tmp.append(sim)
                #print(sim)
            except KeyError as e:
                print(e)
        max_value = max(sim_tmp)
        max_index = sim_tmp.index(max_value)
        group_tmp.append(K_means['分群結果'][max_index])
        
    input_W2V['分群結果'] = group_tmp
    
    #print(input_W2V)
    
    model_input = pd.DataFrame()
    Group = list(range(0,270))
    model_input = pd.DataFrame(columns=Group,index = text_table.index)
    model_input.insert(0, column="斷詞結果", value=text_table['word_text'][0])
    
    
    str_tmp = text_table['word_text'][0].split(",")

    clustering = []
    for i in range(len(str_tmp)):
        for idx in input_W2V.index:
            if str_tmp[i] == idx:
                clustering.append(int(input_W2V.loc[idx]['分群結果'])) 

    #display(clustering)
    result = pd.value_counts(clustering)
    result = result.to_frame(name="times")

    for col in model_input.columns:
        for i in result.index:
            if i == col:
                model_input.loc[0, col] = result.loc[i,'times'] 
    #print(model_input)
    
    model_input = model_input.drop(['斷詞結果'],axis = 1 )
    model_input.fillna(0,inplace=True)
    #print(model_input)
    
    model0 = lgb.Booster(model_file=currentDir+"/data/Lgbm_0.txt")
    model1 = lgb.Booster(model_file=currentDir+"/data/Lgbm_1.txt")
    model2 = lgb.Booster(model_file=currentDir+"/data/Lgbm_2.txt")
    model3 = lgb.Booster(model_file=currentDir+"/data/Lgbm_3.txt")
    model4 = lgb.Booster(model_file=currentDir+"/data/Lgbm_4.txt")
    
    #print("loading...")
    result = []
    result.append(float(model0.predict(model_input)))
    result.append(float(model1.predict(model_input)))
    result.append(float(model2.predict(model_input)))
    result.append(float(model3.predict(model_input)))
    result.append(float(model4.predict(model_input)))
    #print(result)
    
    
    if max(result) >= 0.6:
        print("危急案件")
    else:
        print("一般案件")
    
    #time.sleep(50)
    #os.wait()


if '__main__' == __name__:
    #print('進來惹！')
    # 1.input："一段陳情文"
    # 2.用CKIIP做斷詞
    # 3.各斷詞和alldata_K-means_v2計算隸屬的group
    # 轉成給LGBM的input
    
    # input:
    test_input = sys.argv[1]
    #test_input = "北區公園北路5號附近，涼亭角落的地方時常有一位遊民在那邊當自己的家，遊民在那邊喝酒還會把玻璃酒瓶打碎在步道上，恐容易造成他人危險，遊民還會把自己的棉被堵在小運河造成堵塞，通報員警前往也沒有用，一直浪費資源，請局處強制安置遊民，也請工務局把涼亭的石桌石椅拆除不要讓遊民在那邊喝酒，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾"
    #test_input = "北區公園北路5號附近，煩請相關單位卓處 承辦可聯繫民眾，並請結案時將處理結果電話回覆民眾"

    ana(test_input)


    
