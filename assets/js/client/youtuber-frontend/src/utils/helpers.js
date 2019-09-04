import TimeAgo from "javascript-time-ago";
import en from 'javascript-time-ago/locale/en'

export function unescapeHTML(string) {
    var elt = document.createElement("span");
    elt.innerHTML = string;
    return elt.innerText;
}

export function toTimestamp(datetime) {
    const createdAt = new Date(datetime).getTime();
    TimeAgo.addLocale(en);
    const timeAgo = new TimeAgo('en-US');
    return timeAgo.format(createdAt);
}