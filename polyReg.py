# for loop mit verschiedenen Polynomen und Graphen

import numpy as np
import matplotlib.pyplot as plt
from sklearn.metrics import r2_score as r2

# np.random.seed(5) # same random data every time

syntaxTest = np.random.normal(50.0, 10.0, 1000)
pageSpeeds = np.random.normal(3.0, 1.0, 1000)
purchaseAmount = syntaxTest / pageSpeeds / 3

for i in range(3):
    print(pageSpeeds[i])
    print((syntaxTest / pageSpeeds)[i] == purchaseAmount[i])

# Try different polynomial orders. Can you get a better fit with higher orders?
# Do you start to see overfitting, even though the r-squared score looks good for
# this particular data set?

plt.scatter(pageSpeeds, purchaseAmount)
plt.show()

polyn = np.poly1d(np.polyfit(pageSpeeds, purchaseAmount, 3)) # polyn kann als Fkt genutzt werden, poly1d ist Konstruktor einer Polynomfkt
print(polyn)

x = np.linspace(-1, 7, 1000)
plt.plot(x, polyn(x), c = "r")
plt.show()

for polyDegr in range(10):
    plt.scatter(pageSpeeds, purchaseAmount)
    polyn = np.poly1d(np.polyfit(np.array(pageSpeeds), np.array(purchaseAmount), polyDegr))
    plt.plot(x, polyn(x), c = "r")
    print(r2(purchaseAmount, polyn(x)))
    plt.show()



# def polyn(x, coeff):
#     n = len(coeff)
#     re = 0
#     for i in range(n):
#         re += coeff[n - i] * (x ** (n - i))
#         # coeff[0] * (x ** 2) + coeff[1] * x + coeff[2]
#     return re
#
# print(polyn(5, [2, 4])) # testing: (2 * 2 ** 2) + (4 * 2 ** 1) + (6 * 2 ** 0)
