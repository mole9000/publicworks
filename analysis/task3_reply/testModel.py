#!/usr/bin/python
# -*- coding: utf-8 -*-

#pip install ckiptagger
#pip install tensorflow
#pip install gdown #好像也沒用到
from ckiptagger import data_utils, construct_dictionary, WS, POS, NER #POS表https://ckip.iis.sinica.edu.tw/CKIP/paper/poslist.pdf
CKIPsourse = "/content/my-web-app/analysis/task2_importance/data/data_CKIP"
ws = WS(CKIPsourse)
pos = POS(CKIPsourse)
ner = NER(CKIPsourse)
import joblib, sys, os

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

    
def predict(dataStr,TF,MODEL):

    #print(dataStr)
    #===CKIP處理
    ws_results = ws([dataStr])
    pos_results = pos(ws_results)
    #ner_results = ner(ws_results, pos_results)
    new_result_word = []
    new_result_pos = []
    stop_pos = ['Nep', 'Nh', 'Nb']
    for j in range(0,len(ws_results[0])):
        ckip_word = ws_results[0][j]
        ckip_pos = pos_results[0][j]
        if (ckip_pos[0] == 'N' or ckip_pos[0] == 'V' or ckip_pos[0] == 'A' or ckip_pos[0] == 'D') and ckip_pos not in stop_pos :
            new_result_word.append(ckip_word)
            new_result_pos.append(ckip_pos)
    #print(",".join(new_result_word))
    #print(",".join(new_result_pos))
    dataStr = " ".join(new_result_word)

    #===預測
    test_features = TF.transform([dataStr])
    predicted_labels = MODEL.predict(test_features)
    return predicted_labels

def ModelsPredict(dataStr):
    currentDir = os.path.dirname(os.path.abspath(__file__))
    # 載入模型
    MODEL_SVM = joblib.load(currentDir+'\model_SVM.pkl')   #訓練測試準確度: 0.6154415392205229
    MODEL_RandomForest = joblib.load(currentDir+'\model_RandomForest.pkl')     #訓練測試準確度: 0.6715589541193883
    MODEL_NB_Bernoulli = joblib.load(currentDir+'\model_NB_Bernoulli.pkl')     #訓練測試準確度: 0.6631721756290084
    MODEL_NB_Multinomial = joblib.load(currentDir+'\model_NB_Multinomial.pkl') #訓練測試準確度: 0.659595461272817
    MODEL_DecisionTree = joblib.load(currentDir+'\model_DecisionTree.pkl')     #訓練測試準確度: 0.6534287123828317
    MODEL_NB_Complement = joblib.load(currentDir+'\model_NB_Complement.pkl')   #訓練測試準確度: 0.6154415392205229
    TF = joblib.load(currentDir+'\\tf.pkl')

    result = []
    predict_result = predict(dataStr, TF, MODEL_SVM)[0]
    if predict_result not in result: result.append(predict_result)
    #print(ClassName[predict_result])
    predict_result = predict(dataStr, TF, MODEL_RandomForest)[0]
    if predict_result not in result: result.append(predict_result)
    #print(ClassName[predict_result])
    predict_result = predict(dataStr, TF, MODEL_NB_Bernoulli)[0]
    if predict_result not in result: result.append(predict_result)
    #print(ClassName[predict_result])
    predict_result = predict(dataStr, TF, MODEL_NB_Multinomial)[0]
    if predict_result not in result: result.append(predict_result)
    #print(ClassName[predict_result])
    predict_result = predict(dataStr, TF, MODEL_DecisionTree)[0]
    if predict_result not in result: result.append(predict_result)
    #print(ClassName[predict_result])
    predict_result = predict(dataStr, TF, MODEL_NB_Complement)[0]
    if predict_result not in result: result.append(predict_result)
    #print(ClassName[predict_result])

    print(result)

if __name__ == '__main__':

    dataStr = sys.argv[1]
    
    #input
    #dataStr = "派工，安南區安通路六段上，靠近安明路二段的路中有路樹倒塌，請派員前往清除，煩卓處，承辦若不清楚，可聯繫民眾 "
    #dataStr = "安○○現在整座橋的夜燈現在都不會亮,黑暗一片，橋上橋下都不亮,現在安平港灣在舉辦活動，有許多的觀光客，安○○就在正對面卻黑暗一片,非常的可惜,希望可以盡快改進。 另外安平港的夜間燈光設計有待改善，並不會讓人覺得很漂亮可惜了這個風景，相對比高雄愛河灣或大稻程碼頭的夜景燈光設計還要差,希望市政府可以整體改善，不然可惜了這個風景"
    ModelsPredict(dataStr)







