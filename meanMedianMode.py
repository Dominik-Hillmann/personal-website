import numpy as np
import matplotlib.pyplot as plt
from scipy import stats

incomes = np.random.normal(100.0, 20.0, 10000)

plt.hist(incomes, 50)
plt.show()

# Now, find the mean and median of this data.
# In the code block below, write your code, and see if your result makes sense:

print(np.mean(incomes))
print(np.median(incomes))
print(stats.mode(incomes))

# add outlier to the incomes:
incomes = np.append(incomes, [1000000])
print(np.mean(incomes))
print(np.median(incomes))
