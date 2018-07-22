import numpy as np
import matplotlib.pyplot as plt

# Try creating a scatter plot representing random data
# on ageFrom40vs. time spent watching TV. Label the axes.

# dist. of age: unifrom distribution up to 40, then second half of normal dist.
numPeople = 10000

ageTo40 = np.random.uniform(0.0, 40.0, (int) (numPeople / 2))
plt.hist(ageTo40, 50)
plt.show()

ageFrom40 = np.random.normal(40.0, 18.5, numPeople)
ageFrom40 = np.sort(ageFrom40)
ageFrom40 = ageFrom40[(int) (numPeople / 2):] # get second half of normal distribution
plt.hist(ageFrom40, 50)
plt.show()

age = np.append(ageTo40, ageFrom40) # first half uniform, second half normally distributed
plt.hist(age, 50)
plt.show()

timeTVWeek = np.random.normal(21.0, 4.5, 10000)

plt.scatter(age, timeTVWeek)
plt.show()
