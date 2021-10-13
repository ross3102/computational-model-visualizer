# Computational Model Visualizer

Computational model visualizer is a web application designed for the visualization and execution of computational models in a fun, quiz-like environment. Our website features room creation, where the creator can add questions and test cases, and room participants, who join the created rooms with a unique code and build and submit a machine to meet the problem requirements.

## Setup

Our project uses a composer.json file that holds the dependences. To install these files, composer needs to be installed locally, which can be done from either the composer website or from terminal. To install the dependencies, run `composer install`, or look to see if your IDE provides the option to install them for you.

## Instructions

### Creating and destroying a state

To create a state, simply click on the "new state" circle on the UI and drag it to the desired place on the canvas. The browser will then prompt you to input the name of the state.

To destroy a state, drag the created state into the trash box and release. This will additionally delete any transitions tied to the state.

To designate a state as a start state, double click on the state. You should see an arrow that indicates it is a starting state. There cannot be multiple starting states. To designate a state as an end state, right click on the state. There can be multiple end states, and a state can be both a starting and ending state.

### Creating a transition

A transition is created by hovering over the beginning state of the transition and pressing T. A line should appear while T is held that can be used to connect the state that is transitioned to. Hover over the desired end state and release T. The browser will then prompt you for more information about a transition. States can have transitions to themselves, and multiple transitions to other states.

### Execution

To execute your machine, first load an input with the "load input" button, and then press run to have the machine automatically execute, or step to watch a step-by-step visualization of the process. When you are done with your machine and satisfied with the results, press the "submit" button to test the machine against the room creator's test cases and send your score to the creator. The room creator can then view the scores of each room participant for each question.

## Credit

This project was created by a group effort from these four people:

Ross Newman: rzn3102@gmail.com

Nicholas Levin: nicklevin02@gmail.com

Sebastian Wittrock: sebastianwittrock927@gmail.com

Andrew Kum: andrew3840@gmail.com

Please reach out to any of these people for further questions/explanations!
