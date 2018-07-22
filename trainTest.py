# Try measuring the error on the test data using different degree polynomial fits.
# What degree works best?

import numpy as np
from sklearn.metrics import r2_score as rSq
import matplotlib.pyplot as plt
import pylab as pyl

NUM_DATA = 1000
pageSpeeds = np.random.normal(3.0, 1.0, NUM_DATA)
purchaseAmount = np.random.normal(50.0, 30.0, NUM_DATA) / pageSpeeds

plt.scatter(pageSpeeds, purchaseAmount)
plt.show()

# deviding both into test and training data
trainSpeeds = []
trainPurchase = []
testSpeeds = []
testPurchase = []

for dataIndex in range(NUM_DATA):
    if np.random.random() < 0.8:
        trainSpeeds.append(pageSpeeds[dataIndex])
        trainPurchase.append(purchaseAmount[dataIndex])
    else:
        testSpeeds.append(pageSpeeds[dataIndex])
        testPurchase.append(purchaseAmount[dataIndex])

print("Matching lengths? ", len(trainSpeeds) == len(trainPurchase) & len(testSpeeds) == len(testPurchase))

trainSpeedsNp = np.array(trainSpeeds)
trainPurchaseNp = np.array(trainPurchase)

testSpeedsNp = np.array(testSpeeds)
testPurchaseNp = np.array(testPurchase)

x = np.linspace(-1, 10, 10000)
bestFit = -10000

for degree in range(10):
    f = pyl.poly1d(pyl.polyfit(trainSpeedsNp, trainPurchase, degree)) # trained on training data

    plt.scatter(testSpeedsNp, testPurchaseNp) # plotted and tested with test data
    plt.plot(x, f(x), c = "r")
    plt.xlim([0, 8])
    plt.ylim([-40, 200])
    plt.show()

    testR2 = rSq(testPurchaseNp, f(testSpeedsNp))
    trainR2 = rSq(trainPurchaseNp, f(trainSpeedsNp))
    print("r-squared for TEST data, degree ", degree, " with ", testR2) # r2 can get smaller than 0 if it fits the data worse than the mean would
    print("r-squared for TRAINING data, degree ", degree, " with ", trainR2) # r2 can get smaller than 0 if it fits the data worse than the mean would

    if testR2 > bestFit:
        bestFit = testR2
        print("BETTER FIT")
    else:
        print("WORSE FIT")

print("BEST FIT WITH ", bestFit)

"""
Conclusion: the r2 of the test data often rises with the degrees in the low degrees
until there's a point where the fit starts to get worse and worse for the test data.
The fit for the training data continues to rise: fit(testData) falling while fit(trainData) rising --> overfitting
"""
