import numpy as np
from scipy import stats
# create fake data from an A/B-Test on a website with a sample of 10 000 users each
A = np.random.normal(25.0, 5.0, 10000)
B = np.random.normal(26.0, 5.0, 10000)

print(stats.ttest_ind(A, B)) # that's all

# test again with no real change going on:
C = np.random.normal(25.0, 5.0, 10000)
print(stats.ttest_ind(A, C))
