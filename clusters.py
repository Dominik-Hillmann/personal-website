# Things to play with: what happens if you don't scale the data?
# What happens if you choose different values of K?
# In the real world, you won't know the "right" value of K to start with - you'll need to converge on it yourself.

# from numpy import random, array
import numpy as np

#Create fake income/age clusters for N people in k clusters
def createClusteredData(N, k):
    # creates data of N obversations and k artificial clustes
    np.random.seed(10)
    pointsPerCluster = float(N) / k
    X = []
    for i in range(k):
        incomeCentroid = np.random.uniform(20000.0, 200000.0)
        ageCentroid = np.random.uniform(20.0, 70.0)
        for j in range(int(pointsPerCluster)):
            X.append([np.random.normal(incomeCentroid, 10000.0), np.random.normal(ageCentroid, 2.0)])
    X = np.array(X)
    return X

test = createClusteredData(10, 5)
print(test)



import sklearn as skl
from sklearn.cluster import KMeans
import matplotlib.pyplot as plt

data = createClusteredData(100, 5)
model = kMeans(n_clusters = 4)
print(model)

# Note I'm scaling the data to normalize it! Important for good results.
# model = model.fit(skl.preprocessing.scale(data))

# We can look at the clusters each data point was assigned to
# print(model.labels_)

# And we'll visualize it:
plt.figure(figsize = (8, 6))
plt.scatter(data[:, 0], data[:, 1], c = model.labels_.astype(np.float))
# plt.scatter(data[:, 0], data[:, 1])
plt.show()
