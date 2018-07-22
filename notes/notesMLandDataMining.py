"""
***** K-Nearest Neighbours *****
> you already have a scatterplot along any dimensions with classified data points
> a new data point is classified by identifying its k nearest neighbours and then classify the data points depending
  on the class most of the k data points have
"""


"""
***** Dimensionality Reduction *****
> to reduce the number of dimensions/attributes in a data set, one has to find a hyperplane within a space in n dimensions while
  preserving its variance
> exp.: imagine a 3 dimensional space with data points in it. Try to find a 2-dimensional hyperplane within that space
  that preserves best the variance of the 3 dimensional data points
> now the data points' position are projected onto the plane and you have a dimensionality reduced data set

> practical example is image reduction: the 3 dimensions of color are projected down to a value between 0 and 1 for brightness
"""


"""
***** Data Warehousing *****
> What is Data Warehousing? Putting data from many input streams into one big data bank and transforming it beforehand in
  a way that can be queryed and realtions between the data from different input streams can be made, "Big Data"
> ETL, Extract, Transform, Load: is the conventional way of doing data warehousing. You take the wanted data from you input devices,
  then you transform into a format that can be taken by the data bank and load it into the data bank
    > problem: in the case of too much data the transforming is the bottle neck, inconvenient for big data...
> ELT then loads the raw data into the data repository as-is and uses the power of software like Hadoop to transform it afterwards
    > is mostly a distributed database built on Hadoop
"""


"""
***** Reinforcement Learning *****
> Q-Learning
> imagine Pac-Man, it is the agent in this expample
> for each of the states s Pac-Man can be in (ghost, walls, etc. nearby), there is a set of possible actions a Pac-Man can perform (up, down, left, right)
> each pair (a, s) has a value Q which is the metric by which it decides what action to take
> e.g. Pac-Man performs an action and gets eaten by a ghost, then the action performed in this situation gets penalized by a lower Q
  so that the agent is less likely to take the same action the next time the same situation occurs. It learned.
> Vice versa, if favorable things happen to the agent, the Q value of the (a, s) pair gets raised so that the agent is more likely
  to act in the same way
> all Qs for all (a, s) start with value of 0

> exploration problem: to converge on good Q-values by starting with 0 for all Qs and always choosing a with highest Q is inefficient
  to explore the entire space of actions and their consequences
> all this is a "Markov Decesion Process"

> in short, reinforcement learning consists of an exploration phase where correct rewards for actions in certain situations are found
> then there is an optimal action in each situation to take and your agent behaves in an (almost) optimal manner

"""
