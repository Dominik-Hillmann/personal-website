# Modify the code above such that the purchase probability does NOT vary with age,
# making E and F actually independent.

import numpy as np

np.random.seed(0)
numPop = 100000

totals = {20:0, 30:0, 40:0, 50:0, 60:0, 70:0} # hash: totals[20] == 0 gibt es, aber totals[21] nicht
purchases = {20:0, 30:0, 40:0, 50:0, 60:0, 70:0}
totalPurchases = 0
for _ in range(numPop):
    ageDecade = np.random.choice([20, 30, 40, 50, 60, 70]) # alle Altergruppen haben gleiche Prob, dass ausgewählt
    purchaseProbability = 0.5 # float(ageDecade) / 100.0 Änderung, nun ist Prob des Kaufes keine Funktion des Alter mehr
    totals[ageDecade] += 1 # Population um 1 vergrößern
    if (np.random.random() < purchaseProbability):
        totalPurchases += 1
        purchases[ageDecade] += 1

# nun sollten überall gleiche Anzahl Käufe pro Altersgruppe sein
print(totals)
print(purchases)

# Then, confirm that P(E|F) is about the same as P(E), showing that the conditional probability of purchase for a given
# age is not any different than the a-priori probability of purchase regardless of age.
# P(E) prob of having purchased something
# P(F) prob of being in your thirties
# P(E|F) = prob of having bought something given that you're thirty+

PF = totals[30] / numPop
print("P(F) == " + str(PF)) # is roughly 1/6 because it is the proportion of one of the groups

PEF = purchases[30] / totals[30]
print("P(E|F) == " + str(PEF)) # is roughly 0.5: true, because purchaseProbability == 0.5

# theoretically, P(E|F) == roughly P(E) because P(E,F) == P(E) * P(F) because they're roughly independent
print("P(E) == " + str(totalPurchases / numPop))
