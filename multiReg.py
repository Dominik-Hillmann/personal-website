import pandas as pn
import numpy as np
import matplotlib.pyplot as plt


carFrame = pn.read_excel("http://cdn.sundog-soft.com/Udemy/DataScience/cars.xls")
print(carFrame.shape)
print(carFrame)

# Test: correlation between Doors and Cylinders

cylinders = carFrame[["Cylinder"]]
doors = carFrame[["Doors"]]

# print(cylinders.head())
# print(doors.head())

cylinder4Door = carFrame[carFrame["Doors"] == 4]
cylinder2Door = carFrame[carFrame["Doors"] == 2]
# for i in len(carFrame[["Doors"]]):
#     if carFrame[["Cylinder"]][i] == 4:
#         cylinder4Door.append(carFrame[][i])
#     else:
#         cylinder4Door.append(None)
print(cylinder4Door)
print("4 Doors", np.mean(cylinder4Door))
print("2 Doors", np.mean(cylinder2Door))

# all unigue values in carFrame["Doors"]
uValsDoors = carFrame.Doors.unique()
uValsCyls = carFrame.Cylinder.unique()

for numCyl in uValsCyls:
    print("mean for " + str(numCyl) + " cylinders: ")
    print(np.mean(carFrame[carFrame["Cylinder"] == numCyl]))
    # corr


prices = carFrame["Price"]
cyls = carFrame["Cylinder"]
print("Corr: " + str(np.corrcoef(prices, cyls)))
plt.scatter(prices, cyls)
plt.show()

x = np.linspace(2000, 80000, 100000)
for i in range(10):
    polyn = np.poly1d(np.polyfit(prices, cyls, i))
    plt.scatter(prices, cyls)
    plt.plot(x, polyn(x), c = "r")
    plt.show()
