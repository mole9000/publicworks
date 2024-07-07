#!/usr/bin/python
# -*- coding: utf-8 -*-
#另外要pip install xlrd==1.2.0才能讀excel
import pandas as pd
import numpy as np

ClassName = {}
ClassName["61_6"] = "1999工務類 - 9盞以下路燈故障"
ClassName["6_2"] = "建築管理及使用類 - 公寓大廈管理"
ClassName["4_2"] = "道路、人行道、騎樓及排水溝類 - 道路、人行道工程問題"
ClassName["4_99"] = "道路、人行道、騎樓及排水溝類 - 其他"
ClassName["6_1"] = "建築管理及使用類 - 違建查報及拆除"
ClassName["5_99"] = "路燈路樹公園類 - 其他"
ClassName["5_3"] = "路燈路樹公園類 - 路樹修剪、傾倒"
ClassName["4_1"] = "道路、人行道、騎樓及排水溝類 - 道路、人行道凹陷破損回填不實"
ClassName["6_99"] = "建築管理及使用類 - 其他"
ClassName["61_1"] = "1999工務類 - 路面坑洞"
ClassName["6_5"] = "建築管理及使用類 - 建築管理問題"
ClassName["4_5"] = "道路、人行道、騎樓及排水溝類 - 排水溝溝蓋破損、鬆動、遺失"
ClassName["6_3"] = "建築管理及使用類 - 建物公共安全"
ClassName["5_4"] = "路燈路樹公園類 - 車輛違規進入公園"
ClassName["5_6"] = "路燈路樹公園類 - 公園、綠地、安全島髒亂"
ClassName["5_5"] = "路燈路樹公園類 - 公園設施損毀"
ClassName["5_1"] = "路燈路樹公園類 - 路燈故障不亮"
ClassName["5_2"] = "路燈路樹公園類 - 路燈損毀、傾倒"
ClassName["6_4"] = "建築管理及使用類 - 廣告招牌問題"
ClassName["61_3"] = "1999工務類 - 路面下陷、凹陷"
ClassName["4_3"] = "道路、人行道、騎樓及排水溝類 - 道路、橋樑開闢徵收補償"
ClassName["61_5"] = "1999工務類 - 路樹傾倒"
ClassName["91_2"] = "存參類 - 登記後不予處理"
ClassName["61_2"] = "1999工務類 - 寬頻管線、孔蓋損壞"
ClassName["90_99"] = "其他類 - 其他"
ClassName["4_4"] = "道路、人行道、騎樓及排水溝類 - 地下道問題(含車行及人行)"
ClassName["61_4"] = "1999工務類 - 路面掏空、塌陷"

#==============計算每個類別的數量(由高到低)===============#

df = pd.read_excel("../../task0_preprocess/norepeat_data_withCKIP.xlsx",sheet_name="norepeat_data_withCKIP",usecols=["item","sub_item","ckip_word"]) #,nrows=21
#print(df)
print("資料量:",len(df))

data = []
for i in range(0,len(df)):
    ClassID = str(df.values[i,0]) + '_' + str(df.values[i,1])
    ckipTXT = df.values[i,2]
    if ClassID in ClassName:
        data.append([ClassID, ckipTXT])

print("資料量:",len(data))

savedata = np.array(data)
np.savetxt('alldata.csv', savedata, delimiter=',', fmt='%s')
