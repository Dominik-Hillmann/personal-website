# Tensorflow wasn't actually made for machine Learning
# rather it finds a way to most efficiently distribute a graph of operations over your GPU or a network
# written in C++ (fast) with easy to use Python API
# tensor == vector or matrix of values
# first you construct a graph of what you want to do
# then tensorflow find the optimal way to compute it using your GPU(s)
import tensorflow as tf

# tensorflow way of doing a + b
a = tf.Variable(1, name = "a") # so pass the value as first arg
b = tf.Variable(2, name = "b")

f = a + b # so f can be understood as function f(a, b)
init = tf.global_variables_initializer()
with tf.Session() as session:
    init.run()
    print(f.eval())
