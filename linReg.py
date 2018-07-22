# Try increasing the random variation in the test data,
# and see what effect it has on the r-squared error value.

import numpy as np
import scipy.stats as st
import matplotlib.pyplot as plt

pageSpeeds = np.random.normal(3.0, 2.0, 1000)
purchaseAmount = 100 - (pageSpeeds + np.random.normal(0, 1.0, 1000)) * 3

slope, intercept, r_value, p_value, std_err = st.linregress(pageSpeeds, purchaseAmount)
rSqu = r_value ** 2
print("R^2: " + str(rSqu) + " \nstdDev: " + str(std_err)) # r-squared got smaller because of more insecurity because more variance

def f(x):
    return intercept + slope * x

plt.scatter(pageSpeeds, purchaseAmount)

plt.xlim([0.0, 6.0])
plt.ylim([80.0, 100.0])

plt.axes().grid()

plt.xticks([0, 2, 4, 6])
plt.yticks([80.0, 85.0, 90.0, 95.0, 100.0])

plt.xlabel("page speeds")
plt.ylabel("purchase amount")

plt.plot(pageSpeeds, f(pageSpeeds), c = "r")

plt.show()
