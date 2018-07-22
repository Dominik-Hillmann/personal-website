"""
***** Bias/Variance-Tradeoff *****
> Error = sqr(Bias) + Variance
> high variance means that your predictions are right on average, but they are scattered far around that
> high bias means that you don't on average "hit the target", your predictions are skewed
> the overall goal is to reduce error, not bias or variance specifically

> this is a question of under- or overfitting your model to the data
    > a too complex model will have high variance but low bias since it goes out of its way to fit to the sample data
    > a too simple model will have low variance but high bias
    > KNN example: by increasing k, you'll increase the # of neighbours to put a new data point into a class
"""

"""
***** K-Fold Cross Validation against Overfitting *****
> split your data into k random segments and reserve one of them as test data
> train your models on the remaining k - 1 segments
> you'll have some parameter in your model that can be changed like the degree in polynomial regression
  or the k in KNN, then you pick the model that minimizes error on your test data
"""

"""
***** Cleaning Data *****
> most of the time spent as a data scientist is spent on cleaning up your data since if garbage goes into model, garbage will come out
> the need for this has many causes: bugs in the software, attempts to influence the decision of your model, formatting and normalization (addresses, dates),
  getting rid of irrelevant data, etc.
> Always look at your data and question whether your results make sense

> Normalizing numerical data: important so that some things do not count more than other things; some models perform worse if used on data
  where attributed are differently scaled
> some software will have option to normalize/"whiten" your data
> includes things like (yes, no) <=> (1, 0)
> scaling up your results
"""

"""
***** Outliers *****
> depends on what information you want whether you should eliminate outliers or not
> if a data point is two to three SD outside the mean, it is an outlier, but also common sense and eyeballing
"""
