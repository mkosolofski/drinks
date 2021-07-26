import { createStore, applyMiddleware, compose } from 'redux'
import thunk from 'redux-thunk';
import rootReducer from '../reducer'
import persistState from 'redux-sessionstorage'
import immutable from 'immutable';

const createPersistentStore = compose(
  persistState(
    ["auth"],
    {
      serialize: (subset) => {
        Object.keys(subset).forEach((key) => subset[key] = subset[key].toJS());
        return JSON.stringify(subset);
      },
      deserialize: (serializedData) => {
        if (!serializedData) return {};

        let deserialized = JSON.parse(serializedData);

        Object.keys(deserialized).forEach(
          (key) => deserialized[key] = immutable.fromJS(deserialized[key])
        );

        return deserialized;
      }
    }
  )
)(createStore)

const store = (initialState = {}) => (
  createPersistentStore(
    rootReducer,
    initialState,
    compose(
      applyMiddleware(thunk)
    )
  )
);

export default store;
