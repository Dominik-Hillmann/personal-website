import keras
from keras.datasets import mnist
from keras.models import Sequential
from keras.layers import Dense, Dropout
from keras.optimizers import RMSprop

# we load the MNIST data set, which is a bit different here (also a training data set of 60k)
(trainImagesData, trainLabelsData), (testImagesData, testLabelsData) = mnist.load_data()

# now the data needs to be transformed into a format that Keras expects
# 60k obeservations with 28x28 px = 784 px 1D images, so flattened out
trainImages = trainImagesData.reshape(60000, 784)
testImages = testImagesData.reshape(10000, 784) # 10k observations in test data set here
# libraries are happier with floating point numbers
trainImages = trainImages.astype('float32')
testImages = testImages.astype('float32')
# each px is coded as value 0 to 255, since it's now floats we can normalize it from 0 to 1
trainImages /= 255
testImages /= 255

# the labels are converted to a one-hot format, so NN can predict whether one of these vars == 1
trainLabels = keras.utils.to_categorical(trainLabelsData, 10)
testLabels = keras.utils.to_categorical(testLabelsData, 10)


import matplotlib.pyplot as plot
def displaySample(num):
	# again a function to show a sample
    print(trainLabels[num]) # one_hot array of observation num
    label = trainLabels[num].argmax(axis = 0) # convert one_hot encoding back to normal number
    image = trainImages[num].reshape([28, 28]) # reshape array of values back to a 28x28 pic

    plot.title("Sample: %d Label: %d" % (num, label)) # pyplot, give it its legend
    plot.imshow(image, cmap = plot.get_cmap("gray_r"))
    plot.show()

import random
displaySample(random.randrange(0, len(trainImages)))


# now we set up the model: easy as that:
model = Sequential() # from keras.models
# add a layer of 512 neurons, with input from 784 inputs (px), they fire according to relu function
model.add(Dense(512, activation = 'relu', input_shape = (784, )))
# final classification as one of ten possibles according to softmax
model.add(Dense(10, activation = 'softmax'))

# and a nice summary
model.summary()

# now we define the loss function
model.compile(
	loss = 'categorical_crossentropy',
	optimizer = RMSprop(),
	metrics = ['accuracy']
)

# this is the actual training of the model
history = model.fit(
	trainImages, trainLabels,
	batch_size = 100, # number of samples propagated (training NN) at one time. Why done? 
	epochs = 10,
	verbose = 2,
	validation_data = (testImages, testLabels)
)

# smaller batch size: less memory usage, faster
# disadvantages smaller batch size: the less accurate the gradient

# now we evaluate using scikit learn, simply testing how many test images are correctly categorized (unseen by NN)
score = model.evaluate(testImages, testLabels, verbose = 1)
print('Test loss:', score[0])
print('Test accuracy:', score[1])