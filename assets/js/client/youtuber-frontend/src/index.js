import React from 'react';
import ReactDOM from 'react-dom';
import App from './containers/AppContainer';
import { Provider } from "react-redux";
import store from "./utils/store";
import './index.css';

document.querySelectorAll('.youtuber').forEach(elem => {
  const youtuberId = elem.getAttribute('youtuberId');
  const cols = elem.getAttribute('cols');
  ReactDOM.render(
    <Provider store={store}>
      <App youtuberId={youtuberId} cols={cols} />
    </Provider>, elem);
});