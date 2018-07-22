"""
***** Basic Mathematical Concepts *****
> Gradient Descent: searches a minimum of a function by searching in the direction of the greatest descent of this function
    > often used by changing the parameters of a model a bit and searching for a point that minimizes error (changing weights in
      neural networks or k in KNN, etc.)
> Autodiff: to know the gradient of the cost curve, you have to know its partial derivates which are expensive for computers
  to compute
    > works well for models with many inputs and few outputs (like neurons)
    > autodiff is a calculus trick that makes computing in these situations faster using the chain rule
> softmax translates the weights of the neuron into a comparable range, e.g. it translates it into a probability that
  it will classify an input a certain into a certain class
"""

"""
***** High-Level Description of Neural Networks *****
> in nature, a neuron is activated (it sends a signal through its axon) as soon as the incoming signals reach a certain
  threshold (through its dendrites)
> cortical columns: neurons are stacked into columns (again divided into mini-columns) that process information in parallel
    > it's coincidentally how GPUs work --> GPUs are good to process these programns
> depending on the connections between neurons, boolean operations can be created, tied together with strengths of these connections
    > mathematically, weights are assigned to the inputs into a neuron, which in turn fires if a threshold is reached
> a perceptron is a layer of these LTUs
> it learns by reinforcing weights, "cells that fire together, wire together"
> Multi-Layer Perceptron have several layers of neurons, so one layer fires a certain way depending on how the layer i - 1
  fired depending on i - 2 fired, etc.
> modern deep neural networks use different functions for a neuron other than a step function (0/1) because it works better
  with gradient descent and converges faster

> in http://playground.tensorflow.org
    > this classifies data points that are either blue or orange
    > the input layer classifies whether something is above 0 or below, whether it is in a certain corner... etc.
        > e.g. x and y position: inputs are y > 0, x > 0, x * y > 0, sqr(x) > c (how far from middle), sin(x) > c (certain area of sin waves)
    > in the next layers, these are combined into more complex areas to categorize and their weight is adjusted positivly
      whether it contributed to correct classification or it didn't (which in turn is depended on whether input neurons
      correctly classified)
    > you can see it becomes very unintuitive very fast, it is emergent behavior (simple building blocks for complex end result)
    > the more so the deeper the layer is, interpretation of the shapes the neuron classifies with is dificult
"""

"""
***** Deep Learning Details *****
> Backpropagation: how are the network's weights actually adjusted?
    > compute the output error
    > compute how much each neuron in the previous layer contributed to the right answer
    > give more weight to those inputs that were right, less to those that were wrong
    > repeat until change in the weights becomes smaller than a certain value or time is up
> Activation function determines output of neuron given the sum of its inputs
    > step function (1 if bigger c, else 0) not used anymore, because it does not have a derivative
    > --> Alternatives: Logistic, Hyperbolic, ReLU (y = 0 if x < 0, else y = x)
> Overfitting:
    > how to avoid: early stopping (as soon as betterment starts to stop); dropout - ignore on each step half of the neurons,
      this forces the network to spread out learning and try to use other neurons to create same shape (tensorflow example)
> Tuning the topology (how many layers and neurons in each of the layers)
    > trial and error
    > from large to small to create smalles network that still gets the job done
    > or from small to large to converge on the first network that manages to get the job done
    > probably okay to use more than needed because of abundant computing resources
"""

"""
***** Important Things to know about Tensorflow *****
> Tensorflow wasn't actually made for machine Learning
> rather it finds a way to most efficiently distribute a graph of operations over your GPU or a network
> written in C++ (fast) with easy to use Python API
> tensor == vector or matrix of values
> first you construct a graph of what you want to do
> then tensorflow find the optimal way to compute it using your GPU(s)

> rough steps to train a neural network:
    > load up training and test data
    > use tf placegolders for the input data --> use same grapg for training and testing
    > use tf variables to store the weights
    > choose an optimizer (e.g.) gradient descent, there are several ways to optimize
    > evaluation with testing data

> input data should be normalized to make activation function work out mathematically best
    > mean of zero with a unit variance
    > scikit learn's StandardScaler does this
"""
