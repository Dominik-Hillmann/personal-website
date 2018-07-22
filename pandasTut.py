# Try extracting rows 5-10 of our DataFrame, preserving only the "Previous Employers" and
# "Hired" columns. Assign that to a new DataFrame, and create a histogram plotting the
# distribution of the previous employers in this subset of the data.

import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

df = pd.read_csv("PastHires.csv")
print(df[["Previous employers", "Hired"]])
# data set only lines 5 to 10
smallDf = (df[["Previous employers", "Hired"]][5:])[:6]
print(smallDf)

# number of occurences for each number of employers
numEmploy = smallDf["Previous employers"].value_counts()
numEmploy.sort_values()
print(numEmploy)
print(numEmploy[0])
print(numEmploy[1])

# and make histogram of it
numEmploy.plot(kind = "bar")
plt.show()
