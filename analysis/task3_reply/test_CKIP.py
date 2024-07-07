#!/usr/bin/python
# -*- coding: utf-8 -*-

#pip install ckiptagger
#pip install tensorflow
#pip install gdown #好像也沒用到
from ckiptagger import data_utils, construct_dictionary, WS, POS, NER #POS表https://ckip.iis.sinica.edu.tw/CKIP/paper/poslist.pdf
CKIPsourse = "C:\\JyunHan\\NCKU_course\\publicworks_case\\task2_importance\\data\\data_CKIP"
ws = WS(CKIPsourse)
pos = POS(CKIPsourse)
ner = NER(CKIPsourse)


text = '傅達仁今將執行安樂死，卻突然爆出自己20年前遭緯來體育台封殺，他不懂自己哪裡得罪到電視台。'
ws_results = ws([text])
pos_results = pos(ws_results)
ner_results = ner(ws_results, pos_results)

print(ws_results[0])
print(pos_results[0])
for name in ner_results[0]:
    print(name)

new_result_word = []
new_result_pos = []
stop_pos = ['Nep', 'Nh', 'Nb']
for j in range(0,len(ws_results[0])):
    ckip_word = ws_results[0][j]
    ckip_pos = pos_results[0][j]
    if (ckip_pos[0] == 'N' or ckip_pos[0] == 'V' or ckip_pos[0] == 'A' or ckip_pos[0] == 'D') and ckip_pos not in stop_pos :
        new_result_word.append(ckip_word)
        new_result_pos.append(ckip_pos)
print(",".join(new_result_word))
print(",".join(new_result_pos))
