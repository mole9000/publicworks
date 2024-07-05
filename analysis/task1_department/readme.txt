- requirements:
pip install transformers tqdm boto3 requests regex -q
pip install torchvision 
pip install keras
pip install scikit-learn
pip install tqdm


- file location:
  parent file(eg. smart city)-----other folders
                                |
                                ----bert.py
                                |
                                ----trained_model


- command example:
  !python drive/MyDrive/smart\ city/bert.py --Subject '民眾反映：  安平區建平路，市議會花燈，其中北門區到佳里區的燈座不亮' \
			--Item "路燈" --Area "安平區" --Place "\N" --path "drive/MyDrive/smart city/" 
  - explain: 
    - subject:case_output.csv 中的'subject' column
    - Item:case_output.csv 中的'sub_item_name' column
    - Area:case_output.csv 中的'area_desc' column
    - place:case_output.csv 中的'subj_place' column
    -   path:trained_model folder 的位置


- output example:
   ['公園管理科', '皮球案件', '使用管理科']
   - explain: 
	最有可能為'公園管理科', 其次是'皮球案件', 最後是'使用管理科'


- warning: 模型不會預測出科室代碼5,8,6,2,16,所以扔進的資料對應到的科室代碼不能丟5,8,6,2,16

