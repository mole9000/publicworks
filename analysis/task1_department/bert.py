import getopt, sys, os

import pandas as pd
import numpy as np

import torch #pip install torchvision
from transformers import BertTokenizerFast #pip install transformers==3.4.0
# from IPython.display import clear_output
import keras
from keras_preprocessing.sequence import pad_sequences
from sklearn.model_selection import train_test_split
from torch.utils.data import TensorDataset, DataLoader, RandomSampler, SequentialSampler
from transformers import BertForSequenceClassification
from tqdm import tqdm, trange
# import matplotlib.pyplot as plt
# % matplotlib inline

from sklearn.metrics import confusion_matrix, ndcg_score, accuracy_score
# import seaborn as sn

currentDir = os.path.dirname(os.path.abspath(__file__))
my_options = "hmo:"
# 'sub_item_name':item
# 'subj_place':place
# 'area_desc':area
new_long_options = ["Subject=", "Item=", "Area=","Place=","path="]
subject = ''
item = ''
area = ''
place = ''
model_path = ''

try:
    args,vals = getopt.getopt(sys.argv[1:], my_options, new_long_options)
    # print("args:",args)
    # print("vals:",vals)
    for my_Argument, my_Value in args:
        if my_Argument in ("--Subject"):
              subject = my_Value
        elif my_Argument in ("--Item"):
              if my_Value != '\\N':
                    item = my_Value
        elif my_Argument in ("--Area"):
              if my_Value != '\\N':
                    area = my_Value
        elif my_Argument in ("--Place"):
              if my_Value != '\\N':
                    place = my_Value
        elif my_Argument in ("--path"):
              model_path = my_Value
except getopt.error as err:
    print (str(err))


subject+=(('。具體地點:'+place)+('。行政區:'+area)+('。事項:'+item))
# print(subject)
model_path += currentDir+'\\trained_model\\model.pth'

# load model
reload_model = BertForSequenceClassification.from_pretrained("bert-base-chinese", num_labels=15)
# reload_optimizer = torch.optim.Adam(optimizer_grouped_parameters,lr=2e-5)
checkpoint = torch.load(model_path, map_location='cpu')
reload_model.load_state_dict(checkpoint['model_state_dict'])
# reload_optimizer.load_state_dict(checkpoint['optimizer_state_dict'])
#device = torch.device("cpu")
device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
#reload_model.cuda(device) #<====我(俊翰)把它改成下面那行 #https://tianchi.aliyun.com/notebook-ai/detail?postId=403046
reload_model.to(device)


# load test data
sentences = ["[CLS] " + subject + " [SEP]"]
labels = [3] # randomly select

# tokenize test data
tokenizer = BertTokenizerFast.from_pretrained("bert-base-chinese")
tokenized_texts = [tokenizer.tokenize(sent) for sent in sentences]
MAX_LEN = 128
# Pad our input tokens
input_ids = pad_sequences([tokenizer.convert_tokens_to_ids(txt) for txt in tokenized_texts],
                          maxlen=MAX_LEN, dtype="long", truncating="post", padding="post")
# Use the BERT tokenizer to convert the tokens to their index numbers in the BERT vocabulary
input_ids = [tokenizer.convert_tokens_to_ids(x) for x in tokenized_texts]
input_ids = pad_sequences(input_ids, maxlen=MAX_LEN, dtype="long", truncating="post", padding="post")
# Create attention masks
attention_masks = []
# Create a mask of 1s for each token followed by 0s for padding
for seq in input_ids:
  seq_mask = [float(i>0) for i in seq]
  attention_masks.append(seq_mask) 

# create test tensors
prediction_inputs = torch.tensor(input_ids)
prediction_masks = torch.tensor(attention_masks)
prediction_labels = torch.tensor(labels)
batch_size = 32  
prediction_data = TensorDataset(prediction_inputs, prediction_masks, prediction_labels)
prediction_sampler = SequentialSampler(prediction_data)
prediction_dataloader = DataLoader(prediction_data, sampler=prediction_sampler, batch_size=batch_size)

## Prediction on test set
# Put model in evaluation mode
reload_model.eval()
# Tracking variables 
predictions = []
# Predict 
for batch in prediction_dataloader:
  # Add batch to GPU
  batch = tuple(t.to(device) for t in batch)
  # Unpack the inputs from our dataloader
  b_input_ids, b_input_mask, b_labels = batch
  # Telling the model not to compute or store gradients, saving memory and speeding up prediction
  with torch.no_grad():
    # Forward pass, calculate logit predictions
    outputs = reload_model(b_input_ids, token_type_ids=None, attention_mask=b_input_mask)
  # Move logits and labels to CPU
  #logits = outputs.logits.detach().cpu().numpy()  #<====我(俊翰)把它改成下面那行 #https://gist.github.com/ktoprakucar/d2b93b55385c72fcb9adfc0714cdbed6
  logits = outputs[0].detach().cpu().numpy()
  # label_ids = b_labels.to('cpu').numpy()  
  # Store predictions and true labels
  predictions.append(logits)
  # true_labels.append(label_ids)

flat_predictions_list = [item for sublist in predictions for item in sublist]
pred = np.argsort(flat_predictions_list,axis=1)[:,-3:]
pred = np.flip(pred,axis=1)[0]
map_back= {0:'皮球案件',1:'工程企劃科',2:'採購品管科',3:'建築管理科',4:'使用管理科',5:'秘書室',6:'人事室',7:'政風室',8:'公園管理科',9:'養護工程科',10:'新建工程科',11:'第一工務大隊',12:'第二工務大隊',13:'第三工務大隊',14:'建築工程科'}
pred = [map_back[i] for i in pred]
print(pred)


