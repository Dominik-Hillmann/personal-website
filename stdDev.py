import numpy as np
import matplotlib.pyplot as plt

incomes = np.random.normal(100.0, 50.0, 10000)

plt.hist(incomes, 100)
plt.show()

print(incomes.std())
print(incomes.var())

incomes = np.random.normal(120.0, 200.0, 10000)
plt.hist(incomes, 100)
plt.show()
