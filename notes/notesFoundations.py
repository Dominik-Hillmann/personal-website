import numpy as np # numpy ist lib füe mathematische Funktionen in Python
import pandas as pd # mit pandas lassen sich Datenstrukturen für die Analyse einfach erschaffen ähnlich derer aus R
import matplotlib.pyplot as plt # zeichnet Grafiken

# Datentyp: Python array:
# wenn eine Funktion für einzelne ints spezifiziert ist und
# man einen array aus Ints eingibt, erhält man einen array der Ints als Ergebnisse
# f([x_1, x_2, ..., x_n]) == [f(x_1), f(x_2), ..., f(x_n)] bei x -> f(x)
# Das gleiche gilt für mathematische Operationen:
# [x_1, ..., x_n] / [y_1, ..., y_n] == [x_1 / y_1, ..., x_n / y_n]

# ***** PANDAS DATENSTRUKTUREN *****
df = pd.read_csv("PastHires.csv") # .csv mithilfe des paths einlesen
print(df[["Previous employers", "Hired"]]) # auswählen aller Elemente, bestimmte Eigenschaften mit Array passender Strings
smallDf = (df[["Previous employers", "Hired"]][5:])[:6] # zweites Auswahlelement sind die Zahlen der Beobachtungen
numEmploy = smallDf["Previous employers"].value_counts() # zählt wie oft jeder Wert in Previous employers vorkommt -> histogram
numEmploy.sort_values()
df = df[["Attribut"] > 0] # BOOLEAN INDEXING
df.shape # Dimensionen des DataFrame
numEmploy.plot(kind = "bar")
plt.show() # histogram

# ***** NUMPY Funktionen
# from scipy import stats ==
import scipy.stats as st
incomes = np.random.normal(100.0, 20.0, 10000) # mit Numpy lassen sich sich Zufallsfunktionen aufrufen und Elemente daraus sampeln
# hier: Normalverteilung mit Stdabweichung von 20, 10 000 Elemente
print(np.mean(incomes)) # mittel aus np
print(np.median(incomes)) # Median aus np
print(stats.mode(incomes)) # Modalwert aber aus scipy
incomes = np.append(incomes, [1000000]) # so werden Werte an Numpy array gehängt, hier Outlier von 100000
x = np.arange(-3, 3, 0.01) # np array von -3 bis 3 in 0.01-Schritten


# ***** STANDARDABWEICHUNG *****
import matplotlib.pyplot as plt
# gegeben eines np Arrays kann man gleich Stdabweichung und Varianz raus ziehen
print(incomes.std())
print(incomes.var())


# ***** PERZENTILE *****
# ein Perzentil ist der Wert, der den eindimensionalen Datensetz in alpha, 1- alpha, alpha element [0, 1] Teil
np.percentile(incomes, 50) # ist Median
np.percentile(incomes, 90) # Wert der Daten in 0.9 und 0.1 teilt


# ***** MATPLOTLIB *****
from scipy.stats import norm
x = np.arange(-3, 3, 0.01)
plt.show(x, norm.pdf(x)) # plt.show() hat Argumente (x, f(x)), plottet jedes Element in np arr x und die Funktion dessen
def f(x):
    return x
def g(x):
    return x * x
plt.show(x, f(x))
# mehrere plots in einem Graph
plt.plot(x, f(x))
plt.plot(x, g(x))
plt.show() # alle Graphen sollten gleiche Input haben
plt.savefig('C:\\Users\\Frank\\MyPlot.png', format='png') # save img
# Änderung der Achsen
axes = plt.axes()
axes.set_xlim([-5, 5]) # Gezeigter Teil x
axes.set_ylim([0, 1.0]) # Gezeigter Teil y
axes.set_xticks([-5, -4, -3, -2, -1, 0, 1, 2, 3, 4, 5]) # angezeigte Zahlen als Array
axes.grid() # ganz einfach ein Gitter an Ticks
# Änderung der Linien
plt.plot(x, f(x), "b--") # blaue Linie dashed
plt.plot(x, g(x), "r:") # gepunktete rote Linie
# Labels der Achsen, Legenden
plt.title('Student Locations')
plt.xlabel("Achse 1")
plt.ylabel("Achse 2")
plt.legend(['Sneetches', 'Gacks'], loc = 2) # loc 1 oben rechts, 2 oben links, ... bis 4
# kind of graphs
plt.bar(range(0,5), values, color= colors)
plt.scatter(X,Y)
plt.hist(incomes, 50)

values = [12, 55, 4, 32, 14]
colors = ['r', 'g', 'b', 'c', 'm']
explode = [0, 0, 0.2, 0, 0]
labels = ['India', 'United States', 'Russia', 'China', 'Europe']
plt.pie(values, colors = colors, labels = labels, explode = explode)


# ***** COVARIANCE, CORRELATION *****
# first, if you divide arrays of the same length, each element will be divided
np.cov(x, y) # returns cov Matriox
np.corrcoef # returns corr matrix
# Verständnis Kovarianz: Man nimmt von den beiden Eigenschaften für alle Beobachtungen die Standardabweichungen
# jetzt werden für jede Beobachtung die Stdabweichungen miteinander multipliziert
# werden oft Abweichungen mit gleichen Vorzeichen multipliziert, wird cov positiv sein : pos Zusammenhangen
# werden oft gegenteilige Vorzeichen multipliziert, wird Cov neg sein: inverser Zusammenhang
# werden gleich oft anteilig gegenteilige und gleiche multipliziert und addiert, werden sie sich tendenz. gegen 0 gegenseitig neutralisieren


# ***** LINEAR REGRESSION *****
import numpy as np
import scipy.stats as st
slope, intercept, r_value, p_value, std_err = st.linregress(pageSpeeds, purchaseAmount)
# r_value isn't squared:
rSquared = r_value ** 2
# how to get the line into the scatter plot
def predict(x):
    return slope * x + intercept
fitLine = predict(pageSpeeds)
plt.scatter(pageSpeeds, purchaseAmount)
plt.plot(pageSpeeds, fitLine, c='r')
plt.show()

# ***** POLYNOMIAL REGRESSION *****
# x and y are are arrays of numbers, both same len
polyn = np.poly1d(np.polyfit(x, y, 4)) # auf diese Weise wird die Funktion für die Polynomfunktion erzeugt
plt.plot(x, polyn(x), c = "r") # hier wird die Funktion vierten Grades geplottet
plt.show()
from sklearn.metrics import r2_score
r2 = r2_score(np.array(np.random.normal(50.0, 10.0, 1000)), polyn(x)) # messen wie gut fittet

# ***** MULTIVARIATE REGRESSION *****
