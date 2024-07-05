#!/usr/bin/python
# -*- coding: utf-8 -*-
import os

FolderList = []
FolderList.append("01-01") #警政及路霸排除類 - 違規停車(車輛佔用道路、人行道、騎樓)
FolderList.append("01-02") #警政及路霸排除類 - 物品佔用道路、人行道、騎樓(含移動式、固定式、招牌、景觀燈)
FolderList.append("01-03") #警政及路霸排除類 - 廢棄車輛佔用道路、人行道、騎樓
FolderList.append("01-04") #警政及路霸排除類 - 違規爭議及投訴(含拖吊)
FolderList.append("01-05") #警政及路霸排除類 - 治安維護
FolderList.append("01-06") #警政及路霸排除類 - 交通管制疏導
FolderList.append("01-07") #警政及路霸排除類 - 監視器問題
FolderList.append("01-99") #警政及路霸排除類 - 其他
FolderList.append("02-01") #環保類 - 水、空氣、環境污染
FolderList.append("02-02") #環保類 - 垃圾清運(含垃圾車及回收車)
FolderList.append("02-03") #環保類 - 廢棄物處理(含回收、掩埋、焚化)
FolderList.append("02-04") #環保類 - 路面清理(含垃圾、油漬、排泄物及動物屍體)
FolderList.append("02-05") #環保類 - 空地空屋髒亂
FolderList.append("02-06") #環保類 - 場所、設備及連續噪音
FolderList.append("02-07") #環保類 - 人、動物及非連續噪音
FolderList.append("02-99") #環保類 - 其他
FolderList.append("03-01") #交通類 - 路邊停車格及停車場問題
FolderList.append("03-02") #交通類 - 停車收費問題
FolderList.append("03-03") #交通類 - 標線問題
FolderList.append("03-04") #交通類 - 號誌、標誌問題
FolderList.append("03-05") #交通類 - 公車問題
FolderList.append("03-06") #交通類 - 自行車問題
FolderList.append("03-07") #交通類 - 捷運問題
FolderList.append("03-08") #交通類 - 違規裁罰
FolderList.append("03-10") #交通類 - 無人機問題
FolderList.append("03-99") #交通類 - 其他
FolderList.append("04-01") #道路、人行道、騎樓及排水溝類 - 道路、人行道凹陷破損回填不實
FolderList.append("04-02") #道路、人行道、騎樓及排水溝類 - 道路、人行道工程問題
FolderList.append("04-03") #道路、人行道、騎樓及排水溝類 - 道路、橋樑開闢徵收補償
FolderList.append("04-04") #道路、人行道、騎樓及排水溝類 - 地下道問題(含車行及人行)
FolderList.append("04-05") #道路、人行道、騎樓及排水溝類 - 排水溝溝蓋破損、鬆動、遺失
FolderList.append("04-06") #道路、人行道、騎樓及排水溝類 - 排水溝淤積清疏
FolderList.append("04-99") #道路、人行道、騎樓及排水溝類 - 其他
FolderList.append("05-01") #路燈路樹公園類 - 路燈故障不亮
FolderList.append("05-02") #路燈路樹公園類 - 路燈損毀、傾倒
FolderList.append("05-03") #路燈路樹公園類 - 路樹修剪、傾倒
FolderList.append("05-04") #路燈路樹公園類 - 車輛違規進入公園
FolderList.append("05-05") #路燈路樹公園類 - 公園設施損毀
FolderList.append("05-06") #路燈路樹公園類 - 公園、綠地、安全島髒亂
FolderList.append("05-99") #路燈路樹公園類 - 其他
FolderList.append("06-01") #建築管理及使用類 - 違建查報及拆除
FolderList.append("06-02") #建築管理及使用類 - 公寓大廈管理
FolderList.append("06-03") #建築管理及使用類 - 建物公共安全
FolderList.append("06-04") #建築管理及使用類 - 廣告招牌問題
FolderList.append("06-05") #建築管理及使用類 - 建築管理問題
FolderList.append("06-99") #建築管理及使用類 - 其他
FolderList.append("07-01") #水利類 - 雨、污水下水道工程
FolderList.append("07-02") #水利類 - 積(淹)水問題
FolderList.append("07-03") #水利類 - 堤防、護岸、擋土牆
FolderList.append("07-04") #水利類 - 山坡地水土保持
FolderList.append("07-99") #水利類 - 其他
FolderList.append("08-02") #教育類 - 幼兒園與學費補助
FolderList.append("08-03") #教育類 - 補教問題
FolderList.append("08-04") #教育類 - 十二年國教
FolderList.append("08-05") #教育類 - 教師介聘及甄選
FolderList.append("08-06") #教育類 - 親子溝通、班級經營
FolderList.append("08-07") #教育類 - 招生入學
FolderList.append("08-08") #教育類 - 英語教育與推廣
FolderList.append("08-99") #教育類 - 其他
FolderList.append("09-01") #衛生醫療類 - 食安
FolderList.append("09-02") #衛生醫療類 - 防疫
FolderList.append("09-03") #衛生醫療類 - 醫事醫療
FolderList.append("09-04") #衛生醫療類 - 心理健康
FolderList.append("09-05") #衛生醫療類 - 登革熱防治
FolderList.append("09-99") #衛生醫療類 - 其他
FolderList.append("10-01") #社會福利救助類 - 低與中低收入戶、急難救助
FolderList.append("10-02") #社會福利救助類 - 社會福利(含兒少、婦女、老人、身障)
FolderList.append("10-03") #社會福利救助類 - 照顧服務管理(含長照照服員及據點)
FolderList.append("10-04") #社會福利救助類 - 復康巴士
FolderList.append("10-05") #社會福利救助類 - 街友問題
FolderList.append("10-06") #社會福利救助類 - 人民團體問題
FolderList.append("10-07") #社會福利救助類 - 家暴及性侵防治
FolderList.append("10-99") #社會福利救助類 - 其他
FolderList.append("11-01") #勞工類 - 勞基法問題(含一例一休)
FolderList.append("11-02") #勞工類 - 勞資爭議協調
FolderList.append("11-03") #勞工類 - 求職與徵才
FolderList.append("11-04") #勞工類 - 職業災害及衛生安全
FolderList.append("11-05") #勞工類 - 移工問題
FolderList.append("11-99") #勞工類 - 其他
FolderList.append("12-01") #經濟發展類 - 違規稽查
FolderList.append("12-02") #經濟發展類 - 工商及公司登記
FolderList.append("12-03") #經濟發展類 - 市場、攤販、夜市問題
FolderList.append("12-04") #經濟發展類 - 能源及公用事業
FolderList.append("12-05") #經濟發展類 - 商圈及市集
FolderList.append("12-99") #經濟發展類 - 其他
FolderList.append("13-01") #文化觀光類 - 圖書館管理
FolderList.append("13-02") #文化觀光類 - 文化中心及藝文活動
FolderList.append("13-03") #文化觀光類 - 古蹟、文資及文化園區管理
FolderList.append("13-04") #文化觀光類 - 旅館、民宿及溫泉區管理
FolderList.append("13-05") #文化觀光類 - 風景區管理
FolderList.append("13-06") #文化觀光類 - 觀光行銷、旅行業及旅展
FolderList.append("13-07") #文化觀光類 - 觀光活動、美食小吃及伴手禮
FolderList.append("13-99") #文化觀光類 - 其他
FolderList.append("14-01") #農林漁畜及動保類 - 森林自然保育
FolderList.append("14-02") #農林漁畜及動保類 - 農地管理
FolderList.append("14-03") #農林漁畜及動保類 - 農務、漁業及畜產
FolderList.append("14-04") #農林漁畜及動保類 - 漁港及近海管理
FolderList.append("14-05") #農林漁畜及動保類 - 動物救援
FolderList.append("14-06") #農林漁畜及動保類 - 動物防疫(含禽流感、狂犬病)
FolderList.append("14-07") #農林漁畜及動保類 - 動物捕捉及收容
FolderList.append("14-99") #農林漁畜及動保類 - 其他
FolderList.append("15-01") #民政類 - 廟會及宗教輔導團
FolderList.append("15-02") #民政類 - 生命事業及殯葬問題
FolderList.append("15-03") #民政類 - 戶政問題
FolderList.append("15-04") #民政類 - 行政區劃、鄰里整編
FolderList.append("15-05") #民政類 - 社區活動中心
FolderList.append("15-06") #民政類 - 兵役問題
FolderList.append("15-99") #民政類 - 其他
FolderList.append("16-01") #都市發展類 - 都市計畫與規劃
FolderList.append("16-02") #都市發展類 - 使用分區
FolderList.append("16-03") #都市發展類 - 購(租)屋補貼及貸款
FolderList.append("16-04") #都市發展類 - 國宅、社會及照顧住宅
FolderList.append("16-99") #都市發展類 - 其他
FolderList.append("17-01") #地政類 - 土地測量
FolderList.append("17-02") #地政類 - 土地登記與地籍問題
FolderList.append("17-03") #地政類 - 市地重劃
FolderList.append("17-04") #地政類 - 開發工程
FolderList.append("17-05") #地政類 - 徵收補償
FolderList.append("17-99") #地政類 - 其他
FolderList.append("18-01") #消防類 - 消防安全設備
FolderList.append("18-02") #消防類 - 災害應變及搶救
FolderList.append("18-03") #消防類 - 緊急救護
FolderList.append("18-99") #消防類 - 其他
FolderList.append("19-01") #新聞及國際關係類 - 有線電視及第三頻道
FolderList.append("19-02") #新聞及國際關係類 - 國際關係
FolderList.append("19-03") #新聞及國際關係類 - 官方Line問題
FolderList.append("19-99") #新聞及國際關係類 - 其他
FolderList.append("20-01") #財稅類 - 稅務問題
FolderList.append("20-02") #財稅類 - 公產管理
FolderList.append("20-03") #財稅類 - 菸酒管理
FolderList.append("20-99") #財稅類 - 其他
FolderList.append("21-01") #人事行政類 - 公務人員考核獎懲
FolderList.append("21-02") #人事行政類 - 考試分發及任免遷調
FolderList.append("21-03") #人事行政類 - 組織編制
FolderList.append("21-99") #人事行政類 - 其他
FolderList.append("22-01") #青年事務類 - 青年就業
FolderList.append("22-02") #青年事務類 - 青年創業
FolderList.append("22-03") #青年事務類 - 青年居住
FolderList.append("22-04") #青年事務類 - 育兒福利0-2歲
FolderList.append("22-05") #青年事務類 - 幼兒教育2-4歲
FolderList.append("22-06") #青年事務類 - 網路公共事務參與
FolderList.append("22-99") #青年事務類 - 公共事務參與
FolderList.append("23-01") #體育類 - 運動設施及產業
FolderList.append("23-02") #體育類 - 運動推廣、賽會及補助
FolderList.append("23-03") #體育類 - 學校體育(學校選手參賽及聘用、補助、場地管理)
FolderList.append("23-99") #體育類 - 其他
FolderList.append("60-01") #行動應用程式(APP) - 大台南公車
FolderList.append("60-02") #行動應用程式(APP) - 旅行台南
FolderList.append("60-03") #行動應用程式(APP) - 台南工作好找
FolderList.append("60-04") #行動應用程式(APP) - 台南水情即時通
FolderList.append("60-05") #行動應用程式(APP) - T-Bike臺南市公共自行車
FolderList.append("60-09") #行動應用程式(APP) - OPEN台南1999
FolderList.append("60-10") #行動應用程式(APP) - 安平GO好行
FolderList.append("60-11") #行動應用程式(APP) - 臺南好停
FolderList.append("60-14") #行動應用程式(APP) - 台南市福利地圖
FolderList.append("60-18") #行動應用程式(APP) - 臺南市道路挖掘行動查報系統
FolderList.append("60-19") #行動應用程式(APP) - 台南水情巡查報APP
FolderList.append("60-20") #行動應用程式(APP) - 南市地政e網通
FolderList.append("60-22") #行動應用程式(APP) - 臺南市立圖書館-WOW愛讀冊
FolderList.append("60-23") #行動應用程式(APP) - 札哈木部落大學
FolderList.append("60-24") #行動應用程式(APP) - 台南環保通
FolderList.append("60-25") #行動應用程式(APP) - 臺南市道路養護資訊系統
FolderList.append("60-99") #行動應用程式(APP) - 其他APP事項
FolderList.append("61-01") #1999-工務類 - 路面坑洞
FolderList.append("61-02") #1999-工務類 - 寬頻管線、孔蓋損壞
FolderList.append("61-03") #1999-工務類 - 路面下陷、凹陷
FolderList.append("61-04") #1999-工務類 - 路面掏空、塌陷
FolderList.append("61-05") #1999-工務類 - 路樹傾倒
FolderList.append("61-06") #1999-工務類 - 9盞以下路燈故障
FolderList.append("62-01") #1999-交通類 - 號誌故障
FolderList.append("62-02") #1999-交通類 - 號誌秒差調整
FolderList.append("62-03") #1999-交通類 - 公車動態LED跑馬燈資訊顯示異常
FolderList.append("63-01") #1999-警政類 - 妨礙安寧
FolderList.append("63-02") #1999-警政類 - 違規停車
FolderList.append("63-03") #1999-警政類 - 騎樓舉發
FolderList.append("63-04") #1999-警政類 - 佔用道路
FolderList.append("63-05") #1999-警政類 - 交通疏導
FolderList.append("63-06") #1999-警政類 - 無人機違規
FolderList.append("64-01") #1999-環保類 - 公園髒亂
FolderList.append("64-02") #1999-環保類 - 古蹟髒亂
FolderList.append("64-03") #1999-環保類 - 風景區髒亂
FolderList.append("64-04") #1999-環保類 - 其他環境髒亂
FolderList.append("64-05") #1999-環保類 - 市區道路路面油漬
FolderList.append("64-06") #1999-環保類 - 連續噪音
FolderList.append("64-07") #1999-環保類 - 空氣污染
FolderList.append("64-08") #1999-環保類 - 道路散落物
FolderList.append("64-09") #1999-環保類 - 大型廢棄物清運
FolderList.append("64-10") #1999-環保類 - 小廣告、旗幟
FolderList.append("64-11") #1999-環保類 - 其他污染舉發
FolderList.append("65-01") #1999-動保類 - 犬貓受傷（不含家畜禽、野生動物）
FolderList.append("65-02") #1999-動保類 - 遊蕩犬隻捕捉管制
FolderList.append("65-03") #1999-動保類 - 其他動物受傷
FolderList.append("66-01") #1999-公用事業類 - 台電--停電、10盞以上路燈不亮、電線掉落、漏電、孔蓋鬆動
FolderList.append("66-02") #1999-公用事業類 - 台水--漏水、停水、消防栓漏水或損壞
FolderList.append("66-03") #1999-公用事業類 - 瓦斯01-管溝修補、孔蓋鬆動
FolderList.append("66-04") #1999-公用事業類 - 瓦斯02-天然氣外洩搶修
FolderList.append("66-05") #1999-公用事業類 - 中華電信-電信孔蓋鬆動、電信線路掉落、電信桿傾倒
FolderList.append("67-01") #1999-水利類 - 地下道積水
FolderList.append("67-02") #1999-水利類 - 人孔蓋或溝蓋聲響、鬆動
FolderList.append("67-03") #1999-水利類 - 人孔蓋凹陷坑洞
FolderList.append("69-01") #Covid-19居家照護服務 - 生活-區政及里鄰長關懷服務
FolderList.append("69-02") #Covid-19居家照護服務 - 生活-垃圾清運
FolderList.append("69-03") #Covid-19居家照護服務 - 生活-採購生活及防疫物資
FolderList.append("69-04") #Covid-19居家照護服務 - 生活-寵物照顧
FolderList.append("69-05") #Covid-19居家照護服務 - 生活-後送就醫
FolderList.append("69-06") #Covid-19居家照護服務 - 生活-孤老弱勢及身障者關懷服務
FolderList.append("69-07") #Covid-19居家照護服務 - 生活-勞工權益
FolderList.append("69-08") #Covid-19居家照護服務 - 生活-學生關懷服務
FolderList.append("69-09") #Covid-19居家照護服務 - 生活-公寓大樓事務
FolderList.append("69-10") #Covid-19居家照護服務 - 健康-健康評估及關懷、遠距醫療、居家送藥
FolderList.append("69-11") #Covid-19居家照護服務 - 健康-防疫物資、居隔解隔、電子圍籬
FolderList.append("69-99") #Covid-19居家照護服務 - 其他
FolderList.append("90-05") #其他類 - 線上申辦
FolderList.append("69-99") #其他類 - 其他
FolderList.append("91-01") #存參類 - 廣告信件
FolderList.append("91-02") #存參類 - 登記後不予處理
FolderList.append("91-03") #存參類 - 不實個資

print(len(FolderList))

#建立所有檔案目錄及default.txt
for FolderName in FolderList:
    path = FolderName
    if not os.path.isdir(path):
        os.makedirs(path)
    txtFile = "default.txt"
    txtFilePath = path+"/"+txtFile
    if not os.path.isfile(txtFilePath):
        f = open(txtFilePath, 'w')
        f.close()










