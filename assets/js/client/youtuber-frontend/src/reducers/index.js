import { LOAD_VIDEOS, SET_COLS, SET_CURRENT_VIDEO, SET_YOUTUBER_ID, SET_PAGE_INFO } from "../constants/action-types";

const initialState = {
    videos: [],
    currentVideoID: null,
    youtuberID: null,
    cols: 4,
    pageInfo: {}
};

function rootReducer(state = initialState, action) {
    if (action.type === LOAD_VIDEOS) {
        return Object.assign({}, state, {
            videos: state.videos.concat(action.videos)
        });
    }
    if (action.type === SET_CURRENT_VIDEO) {
        return Object.assign({}, state, {
            currentVideoID: action.id
        });
    }
    if (action.type === SET_YOUTUBER_ID) {
        return Object.assign({}, state, {
            youtuberID: action.id
        });
    }
    if (action.type === SET_PAGE_INFO) {
        return Object.assign({}, state, {
            pageInfo: action.data
        });
    }
    if (action.type === SET_COLS) {
        return Object.assign({}, state, {
            cols: action.cols
        });
    }
    return state;
};

export default rootReducer;