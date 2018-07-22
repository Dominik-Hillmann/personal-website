import pandas as pd

# get these three columns out of the CSV u.data and store them in the data frame ratings
r_cols = ['user_id', 'movie_id', 'rating']
ratings = pd.read_csv('C:/Users/Dominik/Dropbox/Geteilte-Ordner/Sourcecode/data_science/DataScience-Python3/ml-100k/u.data', sep='\t', names=r_cols, usecols=range(3))
print(ratings.head(30))
# ratings contains every single rating, which user_id did to which movie_id
print()


import numpy as np

movieProperties = ratings.groupby('movie_id') # use column movie_id and group ratings with same movie_id near each other
print(movieProperties.head(5))
movieProperties = movieProperties.agg({'rating': [np.size, np.mean]}) # create
print(movieProperties.head(5))
