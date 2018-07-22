import numpy as np
from sklearn.model_selection import cross_val_score, train_test_split
from sklearn import datasets
from sklearn import svm

iris = datasets.load_iris() # load iris data set against with length & width of pedal & sepal and which one of three species

# first a simple train/test split
# Split the iris data into train/test data sets with 40% reserved for testing
X_train, X_test, y_train, y_test = train_test_split(iris.data, iris.target, test_size = 0.4, random_state = 0)
clf = svm.SVC(kernel = 'linear', C = 1).fit(X_train, y_train) # Build an SVC model for predicting iris classifications using training data
print(clf.score(X_test, y_test)) # how well does this model predict the species in the test data? 96% correctly predicted

scores = cross_val_score(clf, iris.data, iris.target, cv = 5) # this is k Cross Validation with 5 training data sets
print(scores) # array for percentage of correct predictions on test data segment
print(scores.mean()) # 0.98 for 5-fold Cross Validation
