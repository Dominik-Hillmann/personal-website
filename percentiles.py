import numpy as np
import matplotlib.pyplot as plt

testVals = np.random.normal(100.0, 15.0, 10000)

print(np.percentile(testVals, 50) == np.median(testVals))
print(np.percentile(testVals, 90))
print(np.percentile(testVals, 30))

plt.hist(testVals, 25)
plt.show()
