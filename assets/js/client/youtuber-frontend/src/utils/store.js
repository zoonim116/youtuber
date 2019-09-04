import { createStore, applyMiddleware } from "redux";
import rootReducer from "../reducers/index";
import thunk from 'redux-thunk';

const store = createStore(rootReducer, applyMiddleware(thunk));

// TODO Remove after development
window.store = store;

export default store;