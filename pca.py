# iris data set: contains length and width of pedal and sepal, which are 4 dimensions

from sklearn.datasets import load_iris
from sklearn.decomposition import PCA
import pylab as pl
from itertools import cycle

iris = load_iris() # belings to sci kit learn

numSamples, numFeatures = iris.data.shape
print(numSamples) # num of obeservations
print(numFeatures) # number of dimensions --> 4 length and width of pedal and sepal
print(list(iris.target_names))

X = iris.data # put data into matrix X
pca = PCA(n_components = 2, whiten = True).fit(X) # create PCA model for n_components == 2 dimensions after PCA
X_pca = pca.transform(X) # put it into new two-dimensional matrix X_pca

print(pca.components_) # prints out the eigenvectors that define the hyperplane for the PCA
print(pca.explained_variance_ratio_) # this tells you how much of the original variance was managed to be preserved
                                     # in each dimension 92 in the first, another 5 in the second, which means
print(sum(pca.explained_variance_ratio_)) # ... that overall 97 per cent were preserved which in turn means...
# that some of these dimensions varied together so that a lot of the variation in one dimension could be explained by the variation
# in another dimension, so maybe only size or the ratio of width and length actually matters?
