#!/usr/bin/python
# -*- coding: utf-8 -*-
import pandas as pd
import numpy as np
from sklearn.feature_extraction.text import CountVectorizer  #CountVectorizer會計算單字出現在文件的次數
from sklearn.feature_extraction.text import TfidfTransformer #再透過TfidfVectorizer轉換成TFIDF和IDF
from sklearn.feature_extraction.text import TfidfVectorizer #也可以直接使用TfidfTransformer計算TFIDF
from sklearn.model_selection import train_test_split #用於切割資料
import joblib #用於儲存模型
from sklearn import metrics #計算準確度
from sklearn.naive_bayes import MultinomialNB #模型NB - 多項式樸素貝葉斯
from sklearn.naive_bayes import ComplementNB  #模型NB - 補充樸素貝葉斯
from sklearn.naive_bayes import GaussianNB    #模型NB - 高斯樸素貝葉斯 (沒用到-會出錯)
from sklearn.naive_bayes import BernoulliNB   #模型NB - 伯努利樸素貝葉斯
from sklearn.naive_bayes import CategoricalNB #模型NB - 類樸素貝葉斯 (沒用到)
from sklearn.ensemble import RandomForestClassifier #模型RandomForest - 隨機森林
from sklearn.tree import DecisionTreeClassifier #模型DecisionTree - 決策數
from sklearn.svm import SVC #模型SVC

#====取得所有資料(包含標籤)
alldata_X = []
alldata_Y = []
f = open('preprocess/alldata.csv')
for line in f.readlines():
    #print(line)
    try:
        splitRow = line.split(",")
        ClassID = splitRow[0]
        spaceStr = []
        for i in range(1,len(splitRow)):
            spaceStr.append(splitRow[i])
        spaceStr = " ".join(spaceStr)
        if spaceStr != "":
            alldata_X.append(spaceStr)
            alldata_Y.append(ClassID)
    except:
        donothing
    #break
f.close


#=====切割資料為訓練集&測試集
X_train, X_test, Y_train, Y_test = train_test_split(alldata_X,alldata_Y, test_size=0.2,random_state=87) #random_state：是随机数的种子。随机数种子：其实就是该组随机数的编号，在需要重复试验的时候，保证得到一组一样的随机数。比如你每次都填1，其他参数一样的情况下你得到的随机数组是一样的。但填0或不填，每次都会不一样。
#print(len(X_train))
#print(len(Y_train))
#print(len(X_test))
#print(len(Y_test))

#=====TF-IDF計算 & 儲存TFIDF值模型
tf = TfidfVectorizer(sublinear_tf=False, stop_words=None, token_pattern="(?u)\\b\\w+\\b", smooth_idf=True, norm='l2')
tfidf_train = tf.fit_transform(X_train)
tfidf_test = tf.transform(X_test)
joblib.dump(tf, 'tf.pkl')

#===== 訓練 & 儲存模型 & 計算準確度
#NB-Multinomial
clf = MultinomialNB(alpha = 0.001).fit(tfidf_train, Y_train) #訓練訓練集
joblib.dump(clf, 'model_NB_Multinomial.pkl') #----儲存訓練好的模型
predicted_labels = clf.predict(tfidf_test) #預測測試集
score = metrics.accuracy_score(Y_test, predicted_labels)
print("NB-Multinomial Accuracy: ",score)
#NB-Bernoulli
clf = BernoulliNB(alpha = 0.001).fit(tfidf_train, Y_train) #訓練訓練集
joblib.dump(clf, 'model_NB_Bernoulli.pkl') #----儲存訓練好的模型
predicted_labels = clf.predict(tfidf_test) #預測測試集
score = metrics.accuracy_score(Y_test, predicted_labels)
print("NB-Bernoulli Accuracy: ",score)
#NB-Complement
clf = ComplementNB(alpha = 0.01).fit(tfidf_train, Y_train) #訓練訓練集
joblib.dump(clf, 'model_NB_Complement.pkl') #----儲存訓練好的模型
predicted_labels = clf.predict(tfidf_test) #預測測試集
score = metrics.accuracy_score(Y_test, predicted_labels)
print("NB-Complement Accuracy: ",score)
#RandomForest - 隨機森林
clf = RandomForestClassifier(n_estimators=25).fit(tfidf_train, Y_train) #訓練訓練集 #n_estimators：森林中樹的數量
joblib.dump(clf, 'model_RandomForest.pkl') #----儲存訓練好的模型
predicted_labels = clf.predict(tfidf_test) #預測測試集
score = metrics.accuracy_score(Y_test, predicted_labels)
print("RandomForest Accuracy: ",score)
#DecisionTree - 決策數
clf = DecisionTreeClassifier().fit(tfidf_train, Y_train) #訓練訓練集
joblib.dump(clf, 'model_DecisionTree.pkl') #----儲存訓練好的模型
predicted_labels = clf.predict(tfidf_test) #預測測試集
score = metrics.accuracy_score(Y_test, predicted_labels)
print("DecisionTree Accuracy: ",score)
#SVM
clf = SVC(kernel='rbf', probability=True).fit(tfidf_train, Y_train) #訓練訓練集 #C=1,gamma=1.0 #rbf径像核函数/高斯核 #probability是否启用概率估计，bool类型，可选参数，默认为False，这必须在调用fit()之前启用，并且会fit()方法速度变慢。
joblib.dump(clf, 'model_SVM.pkl') #----儲存訓練好的模型
predicted_labels = clf.predict(tfidf_test) #預測測試集
score = metrics.accuracy_score(Y_test, predicted_labels)
print("SVM Accuracy: ",score)





