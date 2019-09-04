import React, { Component } from "react";
import { connect } from "react-redux";
import App from "../components/App";
import { setYouberID, setCols, loadAllVideos } from "../actions/index";

class AppContainer extends Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
    const { youtuberId, cols, setYoutuberId, setCols, loadAllVideos } = this.props;
    setYoutuberId(youtuberId);
    setCols(cols);
    loadAllVideos();
  }

  componentDidUpdate(prevProps) {
    if (prevProps.channelID !== this.props.channelID) {
      const script = document.createElement('script');
      script.src = 'https://apis.google.com/js/platform.js';
      script.id = 'platform';
      document.body.appendChild(script);
    }
  }

  render() {
    return (
      <App youtuberId={this.props.youtuberId}>
        <div className="g-ytsubscribe" data-channelid={this.props.channelID} data-layout="default" data-count="default"></div>
      </App>
    );
  }
}

const mapDispatchToProps = dispatch => {
  return {
    setYoutuberId: id => {
      dispatch(setYouberID(id));
    },
    setCols: cols => {
      dispatch(setCols(cols));
    },
    loadAllVideos: () => {
      dispatch(loadAllVideos());
    }
  };
};


const mapStateToProps = state => {
  return {
    channelID: state.pageInfo.channelID,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(AppContainer);
