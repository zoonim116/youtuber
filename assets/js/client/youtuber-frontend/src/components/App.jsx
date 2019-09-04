import React from "react";
import VideoList from "../containers/VideoList";

const App = (props) => (
  <div className="container">
    {props.children}
    <VideoList />
  </div>
);

export default App;
