from __future__ import print_function # Python 2/3 compatibility
from pymongo import MongoClient
import json
import decimal

client = MongoClient("mongodb+srv://username:password@hostname.net/test?retryWrites=true")
db=client.mmdb

with open("moviedata.json") as json_file:
    
    movies = json.load(json_file)
    
    for movie in movies:
        year = int(movie['year'])
        title = movie['title']
        info = movie['info']
        print("Adding movie:", year, title)
        item={
            'year': year,
            'title': title,
            'info': info
        }
        result=db.movies.insert_one(item)

print('finished loading moviedata.json to MongoDB')
        
