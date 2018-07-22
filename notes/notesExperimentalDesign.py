"""
***** A/B-Tests *****
> are run on websites, where one segment of the users see the old website and another part the changed websites
> then the impact on user behavior is measured
> can be algorithmic, design, pricing changes, etc.
> first, choose what you are trying to influence: times sold, speed, ad clicks, etc.
> variance is the enemy: make sure that the difference between the groups does not come from randomness only
> --> T-Tests, p-values
"""

"""
***** T-Tests, p-values *****
> assuming normal distribution in user behavior
> T-Tests normalize a values and measure along a (kind of) normal distribution how far outside normal behavior a data
  point is: the further outside, the less the probability that it happened by chance alone
> a p-value quantifies this: the lower p, the less the probability that this happened by chance
> high T-Statistic, low p means statistical significance
> chosse your threshhold before the experiment! .05 or .01 most of the time
"""

"""
***** How long to run an experiment? *****
> after your values have become significant, both pos and neg
> after an upper bound of time
> when there is nothing to show you that the value will converge any time soon
> this is important because you should not run more than one experiment at once because they will influence each other
"""

"""
***** Common Mistakes *****
> correlation != causation
> novelty effects: just the fact that something changed can cause significant changes in behavior, so let the experiment
  run long enough to make sure the difference in behavior is really caused by the change itself, not by its novelty
    > to make sure: if change is implemented, run another experiment that reimplements the old state and see if it has
      the same effect as before. If yes, it is a novelty effect
> seasonal effects, like christmas and summer time and associated behavior effects
> make sure the users you select for group A and B are really randomly selected. A non-random selection can happen if they
  are based on customer IDs for example --> A/A-Test to make sure
> data pollution: watch out for bots: you most likely want to measure people's behavior, not the ones of the crawlers
"""
