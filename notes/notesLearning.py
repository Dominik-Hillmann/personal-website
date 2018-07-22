"""
***** Unsupervised vs. Supervised Learning *****

Unsupervised: you don't give it any answers it can learn from.
> these algorithms try to find patterns in your data on their own
> these pattern discoverd for example in "clustering" are called latent variablkes
> examples: clustering movies via their properties and look by what mertrics they cluster together

Supervised: we have a set of answers the model can learn from
> Evaluation: two groups, a larger group of training data and test data that the model
  hasn't seen yet to evaluate how good the model predicts unseen data
> k
"""


"""
***** How does clustering work? *****

k-Means clustering:
> choose k random points in the attributes vector
> each of these "centroids" has data points that are nearest to it
> move the centroids to the middle of the data points that were previously nearest to it
> again, with the new centroid, assign data points of which the centroid is the nearest, to it
> the data points change their assigned centroid probably
> iterate this until the centroid don't move much anymore with each iteration
> this is because they converged into the middle point of data where this cluster has
  some kind of distance to another cluster
> this way, latent variables can be found

How large should k be?
> start with a low k
"""


"""
***** The concept of entropy *****

Definition:
> verbally, measure of "sameness" of dataset
> e.g. data set consists of of things ordered into classes. If most observations belong into one class, entropy is low
> but are there several groups/classes that are roughly the same size, the data set is more disordered and has therefore higher entropy
"""


"""
***** Concepts: Decesion Trees *****

> based on the data, a flow chart is created that shows what value each variable has to have (bools, >/</= when numeric) to maximize
  the likelyhood of being categorized correctly by the dependent variable
> at each step, the ID3 algorithm looks what independent variable has the most entropy in the remaining observations and
  divides the data set via a node in the decesion tree

Random Forests:
> decesion trees are prone to overfitting
> therefore, you create several decesion trees from different subsamples of your data set
> then, you let them predict the dependent variables for the whole data set/you let all of the trees "vote" on how to categorize an observation --> ensemble learning

"""


"""
***** Ensemble Learning *****

> basic idea: you have multiple models, possibly of a different kind for the same data and let them vote in their predection
> one technique for ensemble learning is bagging where you pull different subsets from your data set and train different models on them
> boosting is another one: you take the attributes one model got wrong and pay more attention to them in the subsequent models
> a bucket of models runs several entirely different models (regression, k-Means, etc.) and picks the best
> a stack of models lets these entirely different models vote on how to classify an observation
"""


"""
***** Support Vector Machines *****

> supervised technique - training data used
> classifies by putting observations into clusters
> good choice for high dimensional data
> divides data into clusters by finding the hyperplanes the seperate them (support vectors)
> different "kernels" have different computational cost and can produce cluster borders (hyperplanes) that have different complexity (e.g. linear to polynomial or sigmoid)
"""
