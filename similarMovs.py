import pandas as pd

r_cols = ['user_id', 'movie_id', 'rating']
ratings = pd.read_csv("C:/Users/Dominik/Dropbox/Geteilte-Ordner/Sourcecode/data_science/DataScience-Python3/ml-100k/u.data", sep = '\t', names = r_cols, usecols = range(3), encoding = "ISO-8859-1")
# reads the columns named in r_cols from u.data
# sep is the seperator: here seperated by tabs

m_cols = ['movie_id', 'title']
movies = pd.read_csv("C:/Users/Dominik/Dropbox/Geteilte-Ordner/Sourcecode/data_science/DataScience-Python3/ml-100k/u.item", sep = '|', names = m_cols, usecols = range(2), encoding = "ISO-8859-1")
# dictionary for the movie id to movie name so that we have the movie name instead of the id only

ratings = pd.merge(movies, ratings) # automatically merges via movie_id
print(ratings.head())
print(movies.head())

movieRatings = ratings.pivot_table(index = ['user_id'], columns = ['title'], values = 'rating')
# constructs matrices where the observations are the users and the colums the movies, if the user rated, it is a number != NaN
# print(movieRatings.head())
# print(movieRatings.tail())
# so by selecting a column, a film, you should get a series of users who might have rated the movie
starWarsRatings = movieRatings["Star Wars (1977)"]
print(starWarsRatings.head()) # example

similarMovies = movieRatings.corrwith(starWarsRatings) # how much do the ratings of all other movies correlate with the one of Star Wars?
similarMovies = similarMovies.dropna() # exclude NaN
df = pd.DataFrame(similarMovies)
similarMovies = similarMovies.sort_values(ascending = False) # biggest corrs first
print(df.head())
print(similarMovies.head())


import numpy as np
movieStats = ratings.groupby('title').agg({'rating': [np.size, np.mean]}) # list of movies with number of people rating and mean rating
print(movieStats.head())
popularMovies = movieStats['rating']['size'] >= 100 # list of bools whether >= 100 people rated
movieStats[popularMovies].sort_values([('rating', 'mean')], ascending = False)[:15] # sort list of movies with >= 100 raters by mean rating
