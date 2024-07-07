#!/usr/bin/python
# -*- coding: utf-8 -*-
import pandas as pd
from sklearn.feature_extraction.text import CountVectorizer  #CountVectorizer會計算單字出現在文件的次數
from sklearn.feature_extraction.text import TfidfTransformer #再透過TfidfVectorizer轉換成TFIDF和IDF

#======= 直接使用TfidfVectorizer
from sklearn.feature_extraction.text import TfidfVectorizer #也可以直接使用TfidfTransformer計算TFIDF
d1 = 'a b d e d f a f e fa d s a b n'
d2 = 'a z a f e fa h'
d3 = 'a z a f e fa h'
vectorizer = TfidfVectorizer(sublinear_tf=False, stop_words=None, token_pattern="(?u)\\b\\w+\\b", smooth_idf=True, norm='l2')
tfidf = vectorizer.fit_transform([d1,d2,d3])
df_tfidf = pd.DataFrame(tfidf.toarray(),columns=vectorizer.get_feature_names(), index=['d1', 'd2', 'd3'])
print("TFIDF")
print(df_tfidf) #使用pd來呈現
print(vectorizer.get_feature_names())  #不使用pd來呈現-詞
print(tfidf.toarray())  #不使用pd來呈現-TFIDF
#備註
#token_pattern: 这个参数使用正则表达式来分词，其默认参数为r"(?u)\b\w\w+\b"，其中的两个\w决定了其匹配长度至少为2的单词
#这个参数它的默认值只匹配长度≥2的单词，英文中如'a'會被忽略了一样，一般来说，长度为1的单词在英文中一般是无足轻重的，但在中文里，就可能有一些很重要的单字词，所以修改如"(?u)\\b\\w+\\b"
#stop_words: 是list類型，如["是","的"]，無則None
#norm: 默认为'l2'，可设为'l1'或None，计算得到tf-idf值后，如果norm='l2'，则整行权值将归一化，即整行权值向量为单位向量，如果norm=None，则不会进行归一化。大多数情况下，使用归一化是有必要的。
#smooth_idf: 选择是否平滑计算Idf



#======= 使用CountVectorizer + TfidfTransformer
corpus=["我 来到 北京 清华大学",#第一类文本切词后的结果，词之间以空格隔开  
        "他 来到 了 网易 杭研 大厦",#第二类文本的切词结果  
        "小明 硕士 毕业 与 中国 科学院",#第三类文本的切词结果  
        "我 爱 北京 天安门"]#第四类文本的切词结果  
vectorizer = CountVectorizer(stop_words=None)   #将文本中的词语转换为词频矩阵  
X = vectorizer.fit_transform(corpus)   #计算个词语出现的次数  
word = vectorizer.get_feature_names()  #获取词袋中所有文本关键词  
df_word =  pd.DataFrame(X.toarray(),columns=vectorizer.get_feature_names()) #查看词频结果  
print(df_word)
transformer = TfidfTransformer(smooth_idf=True,norm='l2',use_idf=True)  
tfidf = transformer.fit_transform(X)   #将计算好的词频矩阵X统计成TF-IDF值  
df_word_tfidf = pd.DataFrame(tfidf.toarray(),columns=vectorizer.get_feature_names()) #查看计算的tf-idf
print(df_word_tfidf)
df_word_idf = pd.DataFrame(list(zip(vectorizer.get_feature_names(),transformer.idf_)),columns=['单词','idf']) #查看计算的idf
print(df_word_idf)
