"""
***** User-Based Collaborative Filtering *****
> create a matrix of users and things they've liked/watched/shared
> if you want to recommend something to a user, find the user most similar to him and recommend what's different
> unstable because people's taste changes over time
> can be taken advantage of by agents who want their item recommended by creating a lot of users who view their item


***** Item-Bases Collaborative Filtering *****
> has a few advantages: harder to game the system, often less items than people (less computation)

> go through all movies and find people that watched the same pair of movies and make a list of these people
> go through the ratings these people gave the movies and the more similar these ratings are, the more likely the movie is to be recommended
> sort by similarity strength
"""
